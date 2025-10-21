@extends('layouts.admin')

@section('title', isset($article) ? 'Editar Artículo' : 'Nuevo Artículo')

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

<!-- Simple Text Editor Enhancement -->
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
</script>
@endsection
