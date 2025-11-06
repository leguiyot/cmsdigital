<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la galería de medios centralizada
 * Permite gestionar todas las imágenes del sistema desde un solo lugar
 * Similar al sistema de medios de WordPress
 */
class MediaGalleryController extends Controller
{
    /**
     * Muestra la galería principal con todas las imágenes
     * 
     * @param Request $request - Petición con filtros opcionales
     * @return \Illuminate\View\View - Vista de la galería
     */
    public function index(Request $request)
    {
        $query = CustomMedia::query();
        
        // Filtrar por tipo de archivo si se especifica
        if ($request->filled('type')) {
            $type = $request->get('type');
            if ($type === 'images') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($type === 'documents') {
                $query->whereNotLike('mime_type', 'image/%');
            }
        }
        
        // Filtrar por colección si se especifica
        if ($request->filled('collection')) {
            $query->where('collection_name', $request->get('collection'));
        }
        
        // Ordenar por fecha de creación descendente
        $media = $query->orderBy('created_at', 'desc')->paginate(24);
        
        // Obtener estadísticas
        $totalFiles = CustomMedia::count();
        $totalImages = CustomMedia::where('mime_type', 'like', 'image/%')->count();
        $totalSize = CustomMedia::sum('size');
        $videosCount = CustomMedia::where('mime_type', 'like', 'video/%')->count();
        
        // Crear array de estadísticas para la vista
        $stats = [
            'total_files' => $totalFiles,
            'images_count' => $totalImages,
            'videos_count' => $videosCount,
            'total_size' => $totalSize
        ];
        
        // Obtener colecciones disponibles
        $collections = CustomMedia::select('collection_name')
            ->distinct()
            ->whereNotNull('collection_name')
            ->pluck('collection_name');
        
        return view('admin.media.index', compact(
            'media', 
            'stats',
            'collections'
        ));
    }

    /**
     * Muestra el formulario para subir nuevos archivos
     * 
     * @return \Illuminate\View\View - Vista del formulario de subida
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Procesa la subida de nuevos archivos
     * 
     * @param Request $request - Petición con archivos y metadatos
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Inicializar array de errores
        $errors = [];
        
        try {
            // Validar la petición
            $request->validate([
                'files' => 'required|array',
                'files.*' => 'file|max:10240', // 10MB máximo por archivo
                'collection' => 'required|string|in:gallery,cover,featured,thumbnails'
            ]);

            $uploadedFiles = [];
            $collection = $request->input('collection', 'gallery');

            foreach ($request->file('files') as $file) {
                try {
                    // Verificar que el archivo es válido
                    if (!$file->isValid()) {
                        $errors[] = "Archivo {$file->getClientOriginalName()} no es válido";
                        continue;
                    }

                    // Crear el modelo de media
                    $media = new CustomMedia();
                    $media->model_type = 'App\\Models\\Article'; // Tipo por defecto
                    $media->model_id = 0; // Sin asociar a modelo específico
                    $media->collection_name = $collection;
                    $media->name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $media->file_name = $file->getClientOriginalName();
                    $media->mime_type = $file->getMimeType();
                    $media->disk = 'uploads'; // Disco por defecto
                    $media->size = $file->getSize();
                    $media->save();

                    // Usar Spatie para almacenar el archivo
                    $mediaItem = $media->addMediaFromRequest('files')
                        ->each(function ($fileAdder) use ($collection) {
                            $fileAdder->toMediaCollection($collection);
                        });

                    $uploadedFiles[] = [
                        'id' => $media->id,
                        'name' => $media->file_name,
                        'url' => $media->getUrl(),
                        'size' => $media->human_readable_size
                    ];

                } catch (\Exception $e) {
                    $errors[] = "Error subiendo {$file->getClientOriginalName()}: " . $e->getMessage();
                    Log::error('Error uploading file', [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Preparar respuesta
            $response = [
                'success' => count($uploadedFiles) > 0,
                'uploaded' => $uploadedFiles,
                'errors' => $errors,
                'message' => count($uploadedFiles) > 0 
                    ? 'Archivos subidos exitosamente' 
                    : 'No se pudieron subir archivos'
            ];

            // Detectar si es petición AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json($response);
            }

            // Para peticiones normales, redireccionar
            if (count($uploadedFiles) > 0) {
                return redirect()->route('admin.media.index')
                    ->with('success', $response['message']);
            } else {
                return redirect()->back()
                    ->with('error', 'No se pudieron subir los archivos')
                    ->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors,
                    'message' => 'Error de validación'
                ], 422);
            }

            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in media upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error interno del servidor';
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Muestra los detalles de un archivo específico
     * 
     * @param int $id - ID del archivo de medios a mostrar
     * @return \Illuminate\View\View - Vista con detalles del archivo
     */
    public function show($id)
    {
        // Buscar el archivo por ID
        $media = CustomMedia::findOrFail($id);
        
        // Asegurar que el disco esté definido
        if (empty($media->disk)) {
            $media->update(['disk' => 'uploads']);
            $media->refresh();
        }
        
        return view('admin.media.show', compact('media'));
    }

    /**
     * Actualiza los metadatos de un archivo
     * 
     * @param Request $request - Nuevos metadatos
     * @param int $id - ID del archivo a actualizar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        // Buscar el archivo por ID
        $media = CustomMedia::findOrFail($id);

        // Actualizar propiedades personalizadas
        $media->setCustomProperty('alt_text', $request->input('alt_text'));
        $media->setCustomProperty('description', $request->input('description'));
        $media->save();

        return redirect()->route('admin.media.show', $media->id)
            ->with('success', 'Archivo actualizado exitosamente');
    }

    /**
     * Elimina un archivo del sistema
     * 
     * @param int $id - ID del archivo a eliminar
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Buscar el archivo por ID
            $media = CustomMedia::findOrFail($id);
            $fileName = $media->file_name;
            
            // Eliminar el archivo físico y el registro
            $media->delete();
            
            $message = "Archivo '{$fileName}' eliminado exitosamente";
            
            // Responder según el tipo de petición
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('admin.media.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error deleting media', [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            $errorMessage = 'Error al eliminar el archivo';
            
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.media.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * API endpoint para obtener archivos de media
     * Usado por el selector de medios en formularios
     * 
     * @param Request $request - Petición con parámetros de filtrado
     * @return \Illuminate\Http\JsonResponse - Lista de archivos en formato JSON
     */
    public function api(Request $request)
    {
        $query = CustomMedia::query();
        
        // Filtrar por tipo si se especifica
        if ($request->filled('type')) {
            $type = $request->get('type');
            if ($type === 'images') {
                $query->where('mime_type', 'like', 'image/%');
            }
        }
        
        // Filtrar por colección si se especifica
        if ($request->filled('collection')) {
            $query->where('collection_name', $request->get('collection'));
        }
        
        // Búsqueda por nombre
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Paginación
        $perPage = $request->get('per_page', 20);
        $media = $query->orderBy('created_at', 'desc')
                      ->paginate($perPage);
        
        // Formatear respuesta
        $formattedMedia = $media->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->file_name,
                'url' => $item->getUrl(),
                'thumb_url' => $item->hasGeneratedConversion('thumb') 
                    ? $item->getUrl('thumb') 
                    : $item->getUrl(),
                'mime_type' => $item->mime_type,
                'size' => $item->human_readable_size,
                'alt_text' => $item->getCustomProperty('alt_text'),
                'collection' => $item->collection_name,
                'created_at' => $item->created_at->format('d/m/Y H:i')
            ];
        });
        
        return response()->json([
            'data' => $formattedMedia,
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
            'total' => $media->total()
        ]);
    }
}
