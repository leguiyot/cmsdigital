<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Controlador para la gestión de artículos del CMS
 * Maneja el CRUD completo de artículos, incluyendo:
 * - Listado con filtros y búsqueda
 * - Creación y edición con carga de imágenes
 * - Publicación y gestión de destacados
 * - Eliminación de artículos
 */
class ArticleController extends Controller
{
    /**
     * Muestra la lista de artículos con filtros de búsqueda
     * Permite filtrar por: texto, estado y sección
     * 
     * OPTIMIZADO: Eager loading mejorado para evitar consultas N+1
     * 
     * @param Request $request - Parámetros de filtrado
     * @return \Illuminate\View\View - Vista con lista paginada de artículos
     */
    public function index(Request $request)
    {
        // Construir consulta base con relaciones necesarias (eager loading optimizado)
        $query = Article::with([
            'author:id,name,email',
            'section:id,name,slug'
        ]);

        // Aplicar filtro por búsqueda de texto en múltiples campos
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%")
                    ->orWhereHas('author', function ($authorQuery) use ($searchTerm) {
                        $authorQuery->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Aplicar filtro por estado del artículo
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Aplicar filtro por sección
        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }

        // Ordenar por fecha de creación más reciente primero
        $query->orderBy('created_at', 'desc');

        // Paginar resultados y mantener parámetros de búsqueda
        $articles = $query->paginate(15)->withQueryString();

        // Obtener secciones activas para el filtro (con caché de 30 minutos)
        $sections = cache()->remember('admin.sections_filter', 1800, function () {
            return Section::active()->orderBy('name')->get(['id', 'name']);
        });

        return view('admin.articles.index', compact('articles', 'sections'));
    }

    /**
     * Muestra el formulario para crear un nuevo artículo
     * 
     * @return \Illuminate\View\View - Vista del formulario de creación
     */
    public function create()
    {
        // Obtener todas las secciones para el selector
        $sections = Section::all();
        return view('admin.articles.form', compact('sections'));
    }

    /**
     * Almacena un nuevo artículo en la base de datos
     * Incluye validación, procesamiento de datos y manejo de imágenes
     * 
     * @param Request $request - Datos del formulario
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function store(Request $request)
    {
        // Validar todos los campos del formulario incluyendo imágenes
        $request->validate([
            'title' => 'required|string|max:255',
            'volanta' => 'nullable|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:draft,review,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'show_author_name' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:10240', // 10MB máximo
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,webp|max:10240',
            'article_videos.*' => 'nullable|mimes:mp4,mov,avi,webm|max:40960', // 40MB máximo por video
        ]);

        // Preparar datos básicos del artículo
        $articleData = $request->only([
            'title',
            'volanta',
            'excerpt',
            'body',
            'section_id',
            'status',
            'seo_title',
            'meta_description',
            'is_featured',
            'allow_comments'
        ]);

        // Mostrar nombre del autor según checkbox
        $articleData['show_author_name'] = $request->has('show_author_name') ? true : false;

        // Manejar fecha de publicación automática
        if ($request->status === 'published' && !$request->published_at) {
            $articleData['published_at'] = now();
        } elseif ($request->published_at) {
            $articleData['published_at'] = $request->published_at;
        }

        // Manejar timestamp de artículo destacado
        if ($request->has('is_featured') && $request->is_featured) {
            $articleData['featured_at'] = now();
        }

        // Procesar meta_keywords y tags como arrays
        if ($request->meta_keywords) {
            $articleData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        }

        if ($request->tags) {
            $articleData['tags'] = array_map('trim', explode(',', $request->tags));
        }

        // Establecer autor actual
        $articleData['author_id'] = Auth::id();

        // Calcular tiempo de lectura (promedio: 200 palabras por minuto)
        $wordCount = str_word_count(strip_tags($request->body));
        $articleData['reading_time'] = max(1, ceil($wordCount / 200));

        // Crear el artículo
        $article = Article::create($articleData);

        // Manejar imagen destacada
        if ($request->hasFile('featured_image')) {
            $article->addMediaFromRequest('featured_image')
                ->toMediaCollection('cover');
        }

        // Manejar subida de imágenes de galería
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $article->addMedia($file)
                    ->toMediaCollection('gallery');
            }
        }

        // Manejar subida de videos
        if ($request->hasFile('article_videos')) {
            foreach ($request->file('article_videos') as $file) {
                $article->addMedia($file)
                    ->withCustomProperties([
                        'is_video' => true,
                        'generate_preview' => true,
                        'preview_duration' => 3,
                    ])
                    ->toMediaCollection('videos');
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Muestra un artículo específico en la vista pública
     * Incrementa el contador de vistas de forma asíncrona
     * 
     * OPTIMIZADO: Implementa caché de artículos publicados (60 minutos)
     * 
     * @param string $slug - Slug único del artículo
     * @return \Illuminate\View\View - Vista del artículo público
     */
    public function show(string $slug)
    {
        // Obtener artículo publicado con relaciones necesarias y caché
        $article = cache()->remember("article.{$slug}", 3600, function () use ($slug) {
            return Article::where('slug', $slug)
                ->published()
                ->with([
                    'author:id,name,email,bio',
                    'section:id,name,slug',
                    'media'
                ])
                ->firstOrFail();
        });

        // Obtener comentarios sin caché (pueden cambiar frecuentemente)
        $comments = $article->comments()
            ->approved()
            ->whereNull('parent_id')
            ->with([
                'user:id,name',
                'replies' => function ($q) {
                    $q->approved()->with('user:id,name');
                }
            ])
            ->latest()
            ->get();

        // Incrementar contador de vistas de forma asíncrona (no bloquea la respuesta)
        dispatch(function () use ($article) {
            $article->incrementViews();
        })->afterResponse();

        return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Muestra el formulario para editar un artículo existente
     * 
     * @param Article $article - Instancia del artículo a editar
     * @return \Illuminate\View\View - Vista del formulario de edición
     */
    public function edit(Article $article)
    {
        // Obtener todas las secciones para el selector
        $sections = Section::all();
        return view('admin.articles.form', compact('article', 'sections'));
    }

    /**
     * Actualiza un artículo existente en la base de datos
     * Incluye manejo de imágenes (agregar/eliminar) y actualización de datos
     * 
     * @param Request $request - Datos del formulario
     * @param Article $article - Instancia del artículo a actualizar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function update(Request $request, Article $article)
    {
        Log::info('UPDATE METHOD STARTED', [
            'article_id' => $article->id,
            'request_method' => $request->method(),
            'has_remove_videos' => $request->has('remove_videos'),
            'remove_videos_raw' => $request->get('remove_videos')
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'volanta' => 'nullable|string|max:255',
            'excerpt' => 'required|string|max:500',
            'body' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'status' => 'required|in:draft,review,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'show_author_name' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,webp|max:10240', // 10MB max
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,webp|max:10240',
            'article_videos.*' => 'nullable|mimes:mp4,mov,avi,webm|max:40960', // 40MB máximo por video
            'remove_featured_image' => 'nullable|boolean',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'nullable|integer|exists:media,id',
            'remove_videos' => 'nullable|string',
        ]);

        $articleData = $request->only([
            'title',
            'volanta',
            'excerpt',
            'body',
            'section_id',
            'status',
            'seo_title',
            'meta_description',
            'is_featured',
            'allow_comments'
        ]);

        // Mostrar nombre del autor según checkbox
        $articleData['show_author_name'] = $request->has('show_author_name') ? true : false;

        // Handle published_at
        if ($request->status === 'published' && !$article->published_at && !$request->published_at) {
            $articleData['published_at'] = now();
        } elseif ($request->published_at) {
            $articleData['published_at'] = $request->published_at;
        } elseif ($request->status !== 'published') {
            $articleData['published_at'] = null;
        }

        // Handle featured_at
        if ($request->has('is_featured')) {
            if ($request->is_featured && !$article->is_featured) {
                // Se está marcando como destacado por primera vez o se re-marca
                $articleData['featured_at'] = now();
            } elseif (!$request->is_featured && $article->is_featured) {
                // Se está quitando de destacados
                $articleData['featured_at'] = null;
            }
        }

        // Process meta_keywords and tags as arrays
        if ($request->meta_keywords) {
            $articleData['meta_keywords'] = array_map('trim', explode(',', $request->meta_keywords));
        } else {
            $articleData['meta_keywords'] = null;
        }

        if ($request->tags) {
            $articleData['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $articleData['tags'] = null;
        }

        // Calculate reading time (average reading speed: 200 words per minute)
        $wordCount = str_word_count(strip_tags($request->body));
        $articleData['reading_time'] = max(1, ceil($wordCount / 200));

        // Handle featured image removal FIRST (before updating article data)
        if ($request->boolean('remove_featured_image')) {
            $article->clearMediaCollection('cover');
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $article->clearMediaCollection('cover'); // Remove old image first
            $article->addMediaFromRequest('featured_image')
                ->toMediaCollection('cover');
        }

        // Update article data after handling media
        $article->update($articleData);

        // Handle gallery images removal
        if ($request->has('remove_gallery_images')) {
            foreach ($request->remove_gallery_images as $mediaId) {
                $media = $article->getMedia('gallery')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Handle new gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $article->addMedia($file)
                    ->toMediaCollection('gallery');
            }
        }

        // Handle video removal - DEBUG
        \Log::info('Video removal debug', [
            'has_remove_videos' => $request->has('remove_videos'),
            'remove_videos_data' => $request->get('remove_videos'),
            'all_request_data' => $request->all()
        ]);

        if ($request->filled('remove_videos')) {
            $videoIds = explode(',', $request->remove_videos);
            $videoIds = array_filter(array_map('trim', $videoIds)); // Limpiar espacios

            foreach ($videoIds as $mediaId) {
                \Log::info('Attempting to remove video', ['media_id' => $mediaId]);
                $media = $article->getMedia('videos')->where('id', $mediaId)->first();
                if ($media) {
                    \Log::info('Video found, deleting', ['media_id' => $mediaId, 'file_name' => $media->file_name]);
                    $media->delete();
                    \Log::info('Video deleted successfully', ['media_id' => $mediaId]);
                } else {
                    \Log::warning('Video not found for deletion', ['media_id' => $mediaId]);
                }
            }
        }

        // Handle new video uploads
        if ($request->hasFile('article_videos')) {
            foreach ($request->file('article_videos') as $file) {
                $article->addMedia($file)
                    ->withCustomProperties([
                        'is_video' => true,
                        'generate_preview' => true,
                        'preview_duration' => 3,
                    ])
                    ->toMediaCollection('videos');
            }
        }

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Elimina un artículo de la base de datos
     * También elimina automáticamente las imágenes asociadas
     * 
     * @param Article $article - Instancia del artículo a eliminar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function destroy(Article $article)
    {
        // Eliminar artículo (las imágenes se eliminan automáticamente por Spatie Media Library)
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artículo eliminado exitosamente.');
    }

    /**
     * Publica un artículo (cambia estado a published)
     * Establece fecha de publicación si no existe
     * 
     * @param Article $article - Artículo a publicar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => $article->published_at ?? now()
        ]);

        return back()->with('success', 'Artículo publicado exitosamente.');
    }

    /**
     * Despublica un artículo (cambia estado a draft)
     * 
     * @param Article $article - Artículo a despublicar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function unpublish(Article $article)
    {
        $article->update(['status' => 'draft']);

        return back()->with('success', 'Artículo despublicado exitosamente.');
    }

    /**
     * Alterna el estado destacado de un artículo
     * Actualiza el timestamp featured_at cuando se marca como destacado
     * 
     * @param Article $article - Artículo a modificar
     * @return \Illuminate\Http\RedirectResponse - Redirección con mensaje de éxito
     */
    public function feature(Article $article)
    {
        // Preparar datos de actualización
        $updateData = ['is_featured' => !$article->is_featured];

        // Si se está marcando como destacado, actualizar timestamp
        if (!$article->is_featured) {
            $updateData['featured_at'] = now();
        } else {
            $updateData['featured_at'] = null;
        }

        $article->update($updateData);

        // Mensaje dinámico según la acción realizada
        $message = $article->is_featured ? 'Artículo removido de destacados.' : 'Artículo marcado como destacado.';

        return back()->with('success', $message);
    }
}
