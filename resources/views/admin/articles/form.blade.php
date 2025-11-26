{{-- 
    Formulario de creación y edición de artículos vista maneja tanto la creación como la edición de artículos en el panel administrativo.
    Incluye funcionalidades avanzadas:
    
    Campos principales:
    - Título, extracto y cuerpo del artículo (con editor enriquecido)
    - Selección de sección y estado de publicación
    - Gestión de fechas de publicación
    
    Gestión de imágenes:
    - Imagen destacada con vista previa y opción de eliminar
    - Galería de imágenes con drag & drop
    - Integración con Spatie Media Library
    
    SEO y metadatos:
    - Título SEO, meta descripción y palabras clave
    - Tags del artículo
    - Configuraciones de comentarios
    
    Funcionalidades JavaScript:
    - Vista previa de imágenes antes de subir
    - Validación de tipos de archivo
    - Interfaz drag & drop para galería
    - Confirmación antes de eliminar imágenes
--}}
@extends('layouts.admin')

@section('title', isset($article) ? 'Editar Artículo' : 'Nuevo Artículo')

@push('styles')
<!-- Font Awesome - Carga diferida para mejor rendimiento -->
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
@endpush

@push('scripts')
<!-- JavaScript del formulario - Carga diferida -->
<script src="{{ asset('js/article-form.js') }}" defer></script>
@endpush

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        {{ isset($article) ? 'Editar Artículo' : 'Nuevo Artículo' }}
    </h1>
    <a href="{{ route('admin.articles.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
        ← Volver a Artículos
    </a>
</div>

<form method="POST" action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}" 
      class="space-y-6" enctype="multipart/form-data">
    @csrf
    @if(isset($article))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título del Artículo *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="{{ old('title', $article->title ?? '') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg"
                           placeholder="Ingresa el título del artículo">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Volanta -->
                <div class="mb-4">
                    <label for="volanta" class="block text-sm font-medium text-gray-700 mb-2">
                        Volanta o Antetítulo
                    </label>
                    <input type="text" id="volanta" name="volanta"
                           value="{{ old('volanta', $article->volanta ?? '') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Ingresa la volanta o antetítulo (opcional)">
                    @error('volanta')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        La volanta es un texto breve que aparece antes del título principal. Si no se especifica, se mostrará el nombre de la sección.
                    </p>
                </div>

                <!-- Excerpt -->
                <div class="mb-4">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Extracto *
                    </label>
                    <textarea id="excerpt" name="excerpt" rows="3" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Resumen breve del artículo (máximo 500 caracteres)">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenido del Artículo *
                    </label>
                    <textarea id="body" name="body" rows="20" required
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Escribe el contenido completo del artículo aquí...">{{ old('body', $article->body ?? '') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración SEO</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Título SEO
                        </label>
                        <input type="text" id="seo_title" name="seo_title"
                               value="{{ old('seo_title', $article->seo_title ?? '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Título optimizado para buscadores">
                        @error('seo_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Descripción
                        </label>
                        <textarea id="meta_description" name="meta_description" rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Descripción para motores de búsqueda (máximo 160 caracteres)">{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                            Palabras Clave
                        </label>
                        <input type="text" id="meta_keywords" name="meta_keywords"
                               value="{{ old('meta_keywords', isset($article) && $article->meta_keywords ? implode(', ', $article->meta_keywords) : '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="palabra1, palabra2, palabra3">
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Featured Image Upload -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Imagen Destacada</h3>
                
                <!-- Current Featured Image (for editing) -->
                @if(isset($article) && $article->getFirstMediaUrl('cover'))
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
                    <div class="relative group">
                        <img src="{{ $article->getFirstMediaUrl('cover', 'medium') }}" 
                             alt="Imagen destacada" 
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-3">
                            <button type="button" onclick="document.getElementById('featured_image').click();" 
                                    class="bg-white hover:bg-gray-100 text-gray-900 px-3 py-1 rounded text-sm border border-gray-200">
                                <i class="fas fa-pencil-alt mr-1"></i> Cambiar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Checkbox para eliminar imagen -->
                    <div class="mt-3">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remove_featured_image" value="1" 
                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">
                                <i class="fas fa-trash text-red-500 mr-1"></i> Eliminar imagen destacada
                            </span>
                        </label>
                    </div>
                </div>
                @endif

                <!-- Upload New Featured Image -->
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ isset($article) && $article->getFirstMediaUrl('cover') ? 'Cambiar Imagen' : 'Subir Imagen Destacada' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors" 
                         onclick="document.getElementById('featured_image').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <span class="relative bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    Subir archivo
                                </span>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, WEBP hasta 10MB
                            </p>
                        </div>
                    </div>
                    <input id="featured_image" name="featured_image" type="file" class="hidden" 
                           accept="image/jpeg,image/png,image/webp" onchange="previewFeaturedImage(this)">
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Preview -->
                <div id="featured_image_preview" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vista Previa</label>
                    <div class="relative">
                        <img id="featured_image_preview_img" src="" alt="Vista previa" 
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="clearFeaturedImagePreview()" 
                                class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Galería de Imágenes</h3>
                
                <!-- Current Gallery Images (for editing) -->
                @if(isset($article) && $article->getMedia('gallery')->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imágenes Actuales</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($article->getMedia('gallery') as $media)
                        <div class="relative group">
                            <img src="{{ $media->getUrl('thumb') }}" 
                                 alt="Imagen de galería" 
                                 class="w-full h-20 object-cover rounded border border-gray-200">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                <button type="button" onclick="removeGalleryImage({{ $media->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white p-1 rounded text-xs">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload Gallery Images -->
                <div>
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                        Agregar Imágenes a la Galería
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors" 
                         onclick="document.getElementById('gallery_images').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium text-blue-600 hover:text-blue-500">Subir múltiples imágenes</span>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP</p>
                        </div>
                    </div>
                    <input id="gallery_images" name="gallery_images[]" type="file" class="hidden" 
                           accept="image/jpeg,image/png,image/webp" multiple onchange="previewGalleryImages(this)">
                    @error('gallery_images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gallery Preview -->
                <div id="gallery_preview" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nuevas Imágenes</label>
                    <div id="gallery_preview_container" class="grid grid-cols-2 gap-3"></div>
                </div>
            </div>

            <!-- Videos Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Videos del Artículo</h3>
                
                <!-- Current Videos (for editing) -->
                @if(isset($article) && $article->getMedia('videos')->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Videos Actuales</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($article->getMedia('videos') as $media)
                        <div class="relative group border border-gray-200 rounded-lg p-3" data-video-id="{{ $media->id }}">
                            <div class="aspect-video bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                                <video class="w-full h-full object-cover rounded-lg" controls>
                                    <source src="{{ $media->getUrl() }}" type="{{ $media->mime_type }}">
                                    Tu navegador no soporta videos.
                                </video>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-600">
                                    <p class="font-medium">{{ $media->file_name }}</p>
                                    <p>{{ number_format($media->size / (1024*1024), 2) }} MB</p>
                                </div>
                                <button type="button" onclick="removeVideo({{ $media->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white p-1 rounded text-xs">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload Videos -->
                <div>
                    <label for="article_videos" class="block text-sm font-medium text-gray-700 mb-2">
                        Agregar Videos
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors" 
                         onclick="document.getElementById('article_videos').click()">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>                                    
                            </svg>
                            <div class="text-xs text-gray-600">
                                <span class="font-medium text-blue-600 hover:text-blue-500">Subir videos</span>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                MP4, MOV, AVI, WEBM hasta 40MB cada uno
                            </p>
                        </div>
                    </div>
                    <input id="article_videos" name="article_videos[]" type="file" class="hidden" 
                           accept="video/mp4,video/mov,video/avi,video/webm" multiple onchange="previewVideos(this)">
                    @error('article_videos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('article_videos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Video Preview -->
                <div id="video_preview" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nuevos Videos - Vista Previa</label>
                    <div id="video_preview_container" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800">
                                Consejos para videos:
                            </h4>
                            <div class="mt-1 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Formatos soportados: MP4, MOV, AVI, WEBM</li>
                                    <li>Tamaño máximo: 40MB por video</li>
                                    <li>Los videos se pueden insertar en el contenido del artículo</li>
                                    <li>Se generarán thumbnails automáticamente (si está disponible FFmpeg)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publish Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración de Publicación</h3>
                
                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado *
                        </label>
                        <select id="status" name="status" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="draft" {{ old('status', $article->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
                                Borrador
                            </option>
                            <option value="review" {{ old('status', $article->status ?? '') === 'review' ? 'selected' : '' }}>
                                En Revisión  
                            </option>
                            <option value="published" {{ old('status', $article->status ?? '') === 'published' ? 'selected' : '' }}>
                                Publicado
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div>
                        <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Sección *
                        </label>
                        <select id="section_id" name="section_id" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Selecciona una sección</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" 
                                        {{ old('section_id', $article->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published At -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Publicación
                        </label>
                        <input type="datetime-local" id="published_at" name="published_at"
                               value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Si está vacío y el estado es "Publicado", se usará la fecha actual.
                        </p>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Etiquetas
                        </label>
                        <input type="text" id="tags" name="tags"
                               value="{{ old('tags', isset($article) && $article->tags ? implode(', ', $article->tags) : '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="etiqueta1, etiqueta2, etiqueta3">
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Separa las etiquetas con comas.
                        </p>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                   {{ old('is_featured', $article->is_featured ?? false) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_featured" class="ml-2 text-sm text-gray-700">
                                Marcar como destacado
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="allow_comments" name="allow_comments" value="1"
                                   {{ old('allow_comments', $article->allow_comments ?? true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="allow_comments" class="ml-2 text-sm text-gray-700">
                                Permitir comentarios
                            </label>
                        </div>

                        <div class="flex items-center mt-2">
                            <input type="checkbox" id="show_author_name" name="show_author_name" value="1"
                                   {{ old('show_author_name', isset($article) ? ($article->show_author_name ?? true) : true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="show_author_name" class="ml-2 text-sm text-gray-700">
                                Mostrar el nombre del autor
                            </label>
                        </div>

                        <p class="mt-1 text-xs text-gray-500">Si está deshabilitado, el autor se mostrará como "Ndi Diario Digital" en la web pública.</p>
                    </div>
                </div>
            </div>

            <!-- Article Stats (only for editing) -->
            @if(isset($article))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Vistas:</span>
                        <span class="text-sm font-medium">{{ $article->views_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tiempo de lectura:</span>
                        <span class="text-sm font-medium">{{ $article->reading_time }} min</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Creado:</span>
                        <span class="text-sm font-medium">{{ $article->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($article->published_at)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Publicado:</span>
                        <span class="text-sm font-medium">{{ $article->published_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="space-y-3">

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ isset($article) ? 'Actualizar Artículo' : 'Crear Artículo' }}
                    </button>
                    
                    @if(isset($article))
                        @if($article->status === 'published')
                            <form method="POST" action="{{ route('admin.articles.unpublish', $article) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md">
                                    Despublicar
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.articles.publish', $article) }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                                    Publicar Ahora
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.articles.feature', $article) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md">
                                {{ $article->is_featured ? 'Quitar Destacado' : 'Marcar Destacado' }}
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.articles.index') }}" 
                       class="block w-full bg-gray-300 hover:bg-gray-400 text-gray-700 text-center font-medium py-2 px-4 rounded-md">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
    