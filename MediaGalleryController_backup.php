<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate    /**
     * Muestra los detalles de un archivo específico
     * 
     * @param CustomMedia $media - Archivo de medios a mostrar
     * @return \Illuminate\View\View - Vista con detalles del archivo
     */
    public function show(CustomMedia $media)t\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controlador para la galería de medios centralizada
 * Permite gestionar todas las imágenes del sistema desde un solo lugar
 * Similar al sistema de medios de WordPress
 */
class MediaGalleryController extends Controller
{
    /**
     * Muestra la galería de medios con filtros y búsqueda
     * 
     * @param Request $request - Parámetros de filtrado y búsqueda
     * @return \Illuminate\View\View - Vista de la galería con medios paginados
     */
    public function index(Request $request)
    {
        // Asegurar que todos los media tengan disco especificado
        Media::whereNull('disk')->orWhere('disk', '')->update(['disk' => 'uploads']);
        
        $query = Media::query();

        // Filtro por tipo de colección
        if ($request->filled('collection')) {
            $query->where('collection_name', $request->collection);
        }

        // Filtro por tipo de archivo
        if ($request->filled('type')) {
            switch ($request->type) {
                case 'image':
                    $query->where('mime_type', 'like', 'image/%');
                    break;
                case 'video':
                    $query->where('mime_type', 'like', 'video/%');
                    break;
                case 'document':
                    $query->whereNotIn('mime_type', ['image/%', 'video/%']);
                    break;
            }
        }

        // Búsqueda por nombre de archivo
        if ($request->filled('search')) {
            $query->where('file_name', 'like', '%' . $request->search . '%');
        }

        // Filtro por fecha
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        // Paginación
        $media = $query->paginate(24)->appends($request->query());

        // Estadísticas
        $stats = [
            'total_files' => Media::count(),
            'total_size' => Media::sum('size'),
            'images_count' => Media::where('mime_type', 'like', 'image/%')->count(),
            'videos_count' => Media::where('mime_type', 'like', 'video/%')->count(),
            'documents_count' => Media::whereNotLike('mime_type', 'image/%')
                                    ->whereNotLike('mime_type', 'video/%')->count(),
        ];

        return view('admin.media.index', compact('media', 'stats'));
    }

    /**
     * Muestra el formulario para subir nuevos archivos a la galería
     * 
     * @return \Illuminate\View\View - Vista del formulario de subida
     */
    public function create()
    {
        return view('admin.media.upload');
    }

    /**
     * Sube múltiples archivos a la galería
     * 
     * @param Request $request - Archivos y metadatos a subir
     * @return \Illuminate\Http\JsonResponse - Respuesta JSON con resultado
     */
    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB máximo
            'collection' => 'nullable|string|in:gallery,cover,documents',
            'alt_text.*' => 'nullable|string|max:255',
            'description.*' => 'nullable|string|max:500',
        ]);

        $uploadedFiles = [];
        $errors = [];
        $collection = $request->get('collection', 'gallery');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                try {
                    // Crear elemento de galería para asociar el archivo
                    $mediaItem = \App\Models\MediaItem::create([
                        'name' => $file->getClientOriginalName(),
                        'description' => $request->input("description.{$index}", ''),
                        'is_gallery_item' => true,
                    ]);
                    
                    // Subir archivo
                    $media = $mediaItem->addMedia($file)
                        ->usingDisk('uploads')
                        ->withCustomProperties([
                            'alt_text' => $request->input("alt_text.{$index}", ''),
                            'description' => $request->input("description.{$index}", ''),
                            'is_gallery_file' => true,
                        ])
                        ->toMediaCollection($collection);

                    $uploadedFiles[] = [
                        'id' => $media->id,
                        'name' => $media->file_name,
                        'url' => $this->getMediaUrl($media),
                        'size' => $media->size,
                        'type' => $media->mime_type,
                    ];

                } catch (\Exception $e) {
                    $errors[] = "Error subiendo {$file->getClientOriginalName()}: " . $e->getMessage();
                }
            }
        }

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => count($uploadedFiles) > 0,
                'uploaded' => $uploadedFiles,
                'errors' => $errors,
                'message' => count($uploadedFiles) . ' archivos subidos exitosamente.',
            ]);
        }

        // Si es un formulario normal, redireccionar con mensaje
        $message = count($uploadedFiles) . ' archivo(s) subido(s) exitosamente.';
        if (!empty($errors)) {
            $message .= ' Errores: ' . implode(', ', $errors);
        }

        return redirect()->route('admin.media.index')->with('success', $message);
    }

    /**
     * Muestra detalles de un archivo específico
     * 
     * @param Media $media - Archivo de medios a mostrar
     * @return \Illuminate\View\View - Vista con detalles del archivo
     */
    public function show(Media $media)
    {
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
     * @param Media $media - Archivo a actualizar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje
     */
    public function update(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Actualizar nombre del archivo
        $media->update(['name' => $request->name]);

        // Actualizar propiedades personalizadas
        $customProperties = $media->custom_properties;
        $customProperties['alt_text'] = $request->alt_text;
        $customProperties['description'] = $request->description;
        $media->update(['custom_properties' => $customProperties]);

        return redirect()->route('admin.media.show', $media)
                        ->with('success', 'Archivo actualizado exitosamente.');
    }

    /**
     * Elimina un archivo de la galería
     * 
     * @param int $id - ID del archivo a eliminar
     * @return \Illuminate\Http\JsonResponse - Respuesta JSON
     */
    public function destroy($id)
    {
        try {
            // Buscar el archivo por ID
            $media = Media::findOrFail($id);
            
            $fileName = $media->file_name;
            
            // Eliminar el archivo físico y el registro
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => "Archivo '{$fileName}' eliminado exitosamente."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API para obtener archivos de la galería (para seleccionar en artículos)
     * 
     * @param Request $request - Parámetros de filtrado
     * @return \Illuminate\Http\JsonResponse - Lista de archivos en JSON
     */
    public function api(Request $request)
    {
        $query = Media::query();

        // Solo imágenes por defecto
        if (!$request->filled('type') || $request->type === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        }

        // Filtro por colección
        if ($request->filled('collection')) {
            $query->where('collection_name', $request->collection);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $query->where('file_name', 'like', '%' . $request->search . '%');
        }

        $media = $query->orderBy('created_at', 'desc')
                      ->take($request->get('limit', 20))
                      ->get()
                      ->map(function ($item) {
                          return [
                              'id' => $item->id,
                              'name' => $item->file_name,
                              'url' => $this->getMediaUrl($item),
                              'thumb_url' => $this->getMediaConversionUrl($item, 'thumb') ?: $this->getMediaUrl($item),
                              'alt_text' => $item->getCustomProperty('alt_text', ''),
                              'description' => $item->getCustomProperty('description', ''),
                              'size' => $item->size,
                              'type' => $item->mime_type,
                              'created_at' => $item->created_at->format('d/m/Y H:i'),
                          ];
                      });

        return response()->json($media);
    }

    /**
     * Obtiene la URL de un archivo de medios de forma segura
     * 
     * @param Media $media - Archivo de medios
     * @return string - URL del archivo o URL de fallback
     */
    private function getMediaUrl(Media $media): string
    {
        try {
            return $media->getUrl();
        } catch (\Exception $e) {
            // Construir URL manualmente como fallback usando host dinámico
            $date = $media->created_at ?? now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $collection = $media->collection_name ?: 'default';
            
            $host = request()->getSchemeAndHttpHost();
            return $host . "/uploads/articles/{$collection}/{$year}/{$month}/{$media->file_name}";
        }
    }

    /**
     * Obtiene la URL de una conversión de medios de forma segura
     * 
     * @param Media $media - Archivo de medios
     * @param string $conversion - Nombre de la conversión
     * @return string|null - URL de la conversión o null si no existe
     */
    private function getMediaConversionUrl(Media $media, string $conversion): ?string
    {
        try {
            if ($media->hasGeneratedConversion($conversion)) {
                return $media->getUrl($conversion);
            }
        } catch (\Exception $e) {
            // Si falla, intentar construir URL manualmente
            $date = $media->created_at ?? now();
            $year = $date->format('Y');
            $month = $date->format('m');
            $collection = $media->collection_name ?: 'default';
            
            // Buscar archivo de conversión manualmente
            $conversionPath = "uploads/articles/{$collection}/{$year}/{$month}/conversions/{$conversion}/{$media->file_name}";
            if (file_exists(public_path($conversionPath))) {
                return url($conversionPath);
            }
        }
        
        return null;
    }
}
