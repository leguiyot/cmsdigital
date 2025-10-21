@extends('layouts.admin')

@section('title', isset($section) ? 'Editar Sección' : 'Nueva Sección')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">
        {{ isset($section) ? 'Editar Sección' : 'Nueva Sección' }}
    </h1>
    <a href="{{ route('admin.sections.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
        ← Volver a Secciones
    </a>
</div>

<form method="POST" action="{{ isset($section) ? route('admin.sections.update', $section) : route('admin.sections.store') }}" 
      class="space-y-6">
    @csrf
    @if(isset($section))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Sección *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name', $section->name ?? '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Ejemplo: Deportes, Política, Tecnología">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Amigable (Slug)
                        </label>
                        <input type="text" id="slug" name="slug"
                               value="{{ old('slug', $section->slug ?? '') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Se genera automáticamente desde el nombre">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Si se deja vacío, se generará automáticamente desde el nombre.
                        </p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Descripción breve de la sección (opcional)">{{ old('description', $section->description ?? '') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                               value="{{ old('seo_title', $section->seo_title ?? '') }}"
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
                                  placeholder="Descripción para motores de búsqueda (máximo 160 caracteres)">{{ old('meta_description', $section->meta_description ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                            Palabras Clave
                        </label>
                        <input type="text" id="meta_keywords" name="meta_keywords"
                               value="{{ old('meta_keywords', isset($section) && $section->meta_keywords ? implode(', ', $section->meta_keywords) : '') }}"
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
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración</h3>
                
                <div class="space-y-4">
                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Sección activa
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">
                        Las secciones inactivas no aparecen en el sitio público.
                    </p>

                    <!-- Featured -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ old('is_featured', $section->is_featured ?? false) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <label for="is_featured" class="ml-2 text-sm text-gray-700">
                            Sección destacada
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">
                        Las secciones destacadas aparecen en el menú principal.
                    </p>

                    <!-- Sort Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Orden de Visualización
                        </label>
                        <input type="number" id="order" name="order" min="0"
                               value="{{ old('order', $section->order ?? 0) }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Número menor = aparece primero. 0 = sin orden específico.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section Stats (only for editing) -->
            @if(isset($section))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Artículos totales:</span>
                        <span class="text-sm font-medium">{{ $section->articles()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Publicados:</span>
                        <span class="text-sm font-medium text-green-600">{{ $section->articles()->where('status', 'published')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Borradores:</span>
                        <span class="text-sm font-medium text-yellow-600">{{ $section->articles()->where('status', 'draft')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Creada:</span>
                        <span class="text-sm font-medium">{{ $section->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($section->updated_at != $section->created_at)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Actualizada:</span>
                        <span class="text-sm font-medium">{{ $section->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                        {{ isset($section) ? 'Actualizar Sección' : 'Crear Sección' }}
                    </button>
                    
                    @if(isset($section))
                        <a href="{{ route('admin.articles.index', ['section' => $section->id]) }}" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-medium py-2 px-4 rounded-md">
                            Ver Artículos de esta Sección
                        </a>
                    @endif

                    <a href="{{ route('admin.sections.index') }}" 
                       class="block w-full bg-gray-300 hover:bg-gray-400 text-gray-700 text-center font-medium py-2 px-4 rounded-md">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- JavaScript for Slug Generation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[\s_-]+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
    
    nameInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated !== 'false') {
            slugInput.value = generateSlug(this.value);
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });

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
