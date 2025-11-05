@extends('layouts.admin')

@section('title', isset($article) ? 'Editar Artículo' : 'Nuevo Artículo')

@push('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <button type="button" onclick="removeFeaturedImage()" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-trash mr-1"></i> Eliminar
                            </button>
                        </div>
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

<!-- Enhanced Text Editor with Image Management -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Character counter for excerpt
    const excerpt = document.getElementById('excerpt');
    const excerptCounter = document.createElement('div');
    excerptCounter.className = 'text-xs text-gray-500 mt-1 text-right';
    excerpt.parentNode.appendChild(excerptCounter);

    function updateExcerptCounter() {
        const length = excerpt.value.length;
        excerptCounter.textContent = `${length}/500 caracteres`;
        excerptCounter.className = length > 500 ? 'text-xs text-red-500 mt-1 text-right' : 'text-xs text-gray-500 mt-1 text-right';
    }

    excerpt.addEventListener('input', updateExcerptCounter);
    updateExcerptCounter();

    // Character counter for meta description
    const metaDesc = document.getElementById('meta_description');
    if (metaDesc) {
        const metaDescCounter = document.createElement('div');
        metaDescCounter.className = 'text-xs text-gray-500 mt-1 text-right';
        metaDesc.parentNode.appendChild(metaDescCounter);

        function updateMetaDescCounter() {
            const length = metaDesc.value.length;
            metaDescCounter.textContent = `${length}/160 caracteres`;
            metaDescCounter.className = length > 160 ? 'text-xs text-red-500 mt-1 text-right' : 'text-xs text-gray-500 mt-1 text-right';
        }

        metaDesc.addEventListener('input', updateMetaDescCounter);
        updateMetaDescCounter();
    }
});

// Featured Image Functions
function previewFeaturedImage(input) {
    const preview = document.getElementById('featured_image_preview');
    const previewImg = document.getElementById('featured_image_preview_img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function clearFeaturedImagePreview() {
    const input = document.getElementById('featured_image');
    const preview = document.getElementById('featured_image_preview');
    
    input.value = '';
    preview.classList.add('hidden');
}

function removeFeaturedImage() {
    if (confirm('¿Estás seguro de que quieres eliminar la imagen destacada?')) {
        // Add a hidden input to mark for deletion
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_featured_image';
        hiddenInput.value = '1';
        form.appendChild(hiddenInput);
        
        // Hide the current image
        const currentImageContainer = event.target.closest('.relative.group').parentElement;
        currentImageContainer.style.display = 'none';
    }
}

// Gallery Images Functions
function previewGalleryImages(input) {
    const preview = document.getElementById('gallery_preview');
    const container = document.getElementById('gallery_preview_container');
    
    // Clear previous previews
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative';
                
                imgContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Vista previa ${index + 1}" 
                         class="w-full h-20 object-cover rounded border border-gray-200">
                    <button type="button" onclick="removeGalleryPreview(this, ${index})" 
                            class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;
                
                container.appendChild(imgContainer);
            }
            
            reader.readAsDataURL(file);
        });
    } else {
        preview.classList.add('hidden');
    }
}

function removeGalleryPreview(button, index) {
    const input = document.getElementById('gallery_images');
    const container = document.getElementById('gallery_preview_container');
    const preview = document.getElementById('gallery_preview');
    
    // Remove the preview element
    button.parentElement.remove();
    
    // Create new FileList without the removed file
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    
    // Hide preview if no files left
    if (input.files.length === 0) {
        preview.classList.add('hidden');
    }
}

function removeGalleryImage(mediaId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
        // Add hidden input to mark for deletion
        const form = document.querySelector('form');
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_gallery_images[]';
        hiddenInput.value = mediaId;
        form.appendChild(hiddenInput);
        
        // Hide the image
        event.target.closest('.relative.group').style.display = 'none';
    }
}

// Drag and Drop functionality
function setupDragAndDrop() {
    const dropZones = document.querySelectorAll('[onclick*="click()"]');
    
    dropZones.forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
        
        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
        });
        
        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-500', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = this.parentElement.querySelector('input[type="file"]');
                input.files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        });
    });
}

// Initialize drag and drop when page loads
document.addEventListener('DOMContentLoaded', setupDragAndDrop);
</script>
@endsection
