{{-- 
    Vista principal de la galería de medios
    
    Permite gestionar todos los archivos multimedia del sistema desde una interfaz centralizada.
    Incluye funcionalidades de:
    - Visualización en grid de imágenes con thumbnails
    - Filtros por tipo, colección y fecha
    - Búsqueda por nombre de archivo
    - Subida múltiple de archivos con drag & drop
    - Estadísticas de uso de almacenamiento
    - Gestión individual de archivos (editar, eliminar)
--}}
@extends('layouts.admin')

@section('title', 'Galería de Medios')

@push('styles')
<style>
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }
    .media-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
    }
    .media-thumbnail {
        width: 100%;
        height: 120px;
        object-fit: cover;
        display: block;
    }
    .media-info {
        padding: 8px;
        border-top: 1px solid #f3f4f6;
    }
    .media-title {
        font-size: 0.75rem;
        color: #374151;
        margin: 0 0 4px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .media-meta {
        font-size: 0.6875rem;
        color: #6b7280;
        margin: 0;
    }
    .media-actions {
        position: absolute;
        top: 4px;
        right: 4px;
        display: flex;
        gap: 2px;
    }
    .media-actions button {
        width: 24px;
        height: 24px;
        border-radius: 2px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0;
    }
    .media-actions .view-btn {
        background: rgba(59, 130, 246, 0.9);
        color: white;
    }
    .media-actions .delete-btn {
        background: rgba(239, 68, 68, 0.9);
        color: white;
    }
    .media-actions button:hover {
        opacity: 0.8;
    }
    .no-files-container {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
    }
    .no-files-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 1rem;
        color: #d1d5db;
    }
</style>
@endpush

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Galería de Medios</h1>
        <p class="text-gray-600 mt-1">Gestiona todas las imágenes y archivos del sistema</p>
    </div>
    <div class="flex space-x-3">
        <!-- Formulario oculto para subir archivos -->
        <form id="uploadForm" method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" style="display: none;">
            @csrf
            <input type="file" id="fileInput" name="files[]" multiple accept="image/*,video/*,.pdf,.doc,.docx">
            <input type="hidden" name="collection" value="gallery">
        </form>
        
        <!-- Botón principal que abre el explorador -->
        <button onclick="document.getElementById('fileInput').click()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Subir Archivos
        </button>
        
        <a href="{{ route('admin.articles.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
            ← Volver a Artículos
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded p-4">
        <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_files'] }}</div>
        <div class="text-sm text-gray-600">Total Archivos</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-4">
        <div class="text-2xl font-semibold text-gray-900">{{ $stats['images_count'] }}</div>
        <div class="text-sm text-gray-600">Imágenes</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-4">
        <div class="text-2xl font-semibold text-gray-900">{{ $stats['videos_count'] }}</div>
        <div class="text-sm text-gray-600">Videos</div>
    </div>
    <div class="bg-white border border-gray-200 rounded p-4">
        <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_size'] / 1024 / 1024, 1) }} MB</div>
        <div class="text-sm text-gray-600">Espacio</div>
    </div>
</div>

<!-- Filtros -->
<div class="bg-white border border-gray-200 rounded p-4 mb-6">
    <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-64">
            <label for="search" class="block text-sm text-gray-700 mb-1">Buscar archivos</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                   placeholder="Nombre del archivo..." 
                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
        </div>
        <div class="min-w-48">
            <label for="type" class="block text-sm text-gray-700 mb-1">Tipo</label>
            <select id="type" name="type" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="">Todos los tipos</option>
                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Imágenes</option>
                <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
                <option value="document" {{ request('type') === 'document' ? 'selected' : '' }}>Documentos</option>
            </select>
        </div>
        <div class="min-w-48">
            <label for="collection" class="block text-sm text-gray-700 mb-1">Colección</label>
            <select id="collection" name="collection" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                <option value="">Todas las colecciones</option>
                <option value="cover" {{ request('collection') === 'cover' ? 'selected' : '' }}>Imágenes destacadas</option>
                <option value="gallery" {{ request('collection') === 'gallery' ? 'selected' : '' }}>Galería</option>
                <option value="documents" {{ request('collection') === 'documents' ? 'selected' : '' }}>Documentos</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                Filtrar
            </button>
            <a href="{{ route('admin.media.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded text-sm">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Grid de archivos -->
@if($media->count() > 0)
    <div class="media-grid mb-6">
        @foreach($media as $file)
            <div class="media-item">
                <div class="relative">
                    @if(str_starts_with($file->mime_type, 'image/'))
                        <img src="{{ $file->hasGeneratedConversion('thumb') ? $file->getUrl('thumb') : $file->getUrl() }}" 
                             alt="{{ $file->getCustomProperty('alt_text', $file->file_name) }}" 
                             class="media-thumbnail">
                    @else
                        <div class="media-thumbnail bg-gray-50 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="media-actions">
                        <button onclick="viewFile({{ $file->id }})" class="view-btn" title="Ver archivo">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button onclick="deleteFile({{ $file->id }})" class="delete-btn" title="Eliminar archivo">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="media-info">
                    <h3 class="media-title">{{ $file->file_name }}</h3>
                    <p class="media-meta">{{ $file->collection_name }} • {{ number_format($file->size / 1024, 1) }} KB</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $media->links() }}
    </div>
@else
    <div class="no-files-container">
        <svg class="no-files-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-sm font-medium text-gray-900 mb-1">No hay archivos</h3>
        <p class="text-sm text-gray-500 mb-4">Comienza subiendo algunos archivos a la galería.</p>
        <button onclick="document.getElementById('fileInput').click()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
            Subir primer archivo
        </button>
    </div>
@endif

<!-- Notificación de subida en progreso -->
<div id="uploadNotification" class="fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-md shadow-lg hidden z-50">
    <div class="flex items-center space-x-2">
        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span id="uploadNotificationText">Subiendo archivos...</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Subida automática al seleccionar archivos
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fileInput');
        const uploadForm = document.getElementById('uploadForm');
        
        // Cuando se seleccionan archivos, subir automáticamente
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                showUploadNotification(e.target.files.length);
                // Subir automáticamente sin confirmación
                setTimeout(() => {
                    uploadForm.submit();
                }, 500); // Pequeña pausa para mostrar la notificación
            }
        });
    });

    function showUploadNotification(fileCount) {
        const notification = document.getElementById('uploadNotification');
        const notificationText = document.getElementById('uploadNotificationText');
        
        let fileText = fileCount === 1 ? 
            'Subiendo 1 archivo...' : 
            `Subiendo ${fileCount} archivos...`;
        
        notificationText.textContent = fileText;
        notification.classList.remove('hidden');
    }

    // Funciones para gestión de archivos existentes
    function viewFile(id) {
        window.location.href = `{{ url('/admin/media') }}/${id}/view`;
    }

    async function deleteFile(id) {
        if (!confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
            return;
        }
        
        try {
            console.log('Intentando eliminar archivo con ID:', id);
            
            const url = `{{ url('/admin/media') }}/${id}`;
            console.log('URL de eliminación:', url);
            
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            });
            
            console.log('Respuesta del servidor:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            console.log('Resultado:', result);
            
            if (result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert('Error al eliminar archivo: ' + result.message);
            }
        } catch (error) {
            console.error('Error completo:', error);
            alert('Error al eliminar archivo: ' + error.message);
        }
    }
</script>
@endpush
