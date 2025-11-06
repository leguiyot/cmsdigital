{{-- 
    Vista para mostrar detalles de un archivo de medios
    
    Muestra información detallada de un archivo específico incluyendo:
    - Imagen o icono del archivo
    - Metadatos (nombre, tamaño, tipo, fecha)
    - Propiedades personalizadas (alt text, descripción)
    - URL directa y opciones de descarga
--}}
@extends('layouts.admin')

@section('title', 'Detalles del Archivo')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detalles del Archivo</h1>
            <p class="text-gray-600 mt-1">{{ $media->file_name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.media.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                ← Volver a Galería
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Previsualización -->
        <div class="bg-white border border-gray-200 rounded p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Previsualización</h2>
            
            <div class="text-center">
                @if(str_starts_with($media->mime_type, 'image/'))
                    @php
                        // Obtener URL dinámicamente
                        try {
                            $imageUrl = $media->getUrl();
                        } catch (\Exception $e) {
                            // Fallback: construir URL manualmente
                            $currentHost = request()->getSchemeAndHttpHost();
                            $relativePath = '/uploads/articles/' . $media->collection_name . '/' . $media->created_at->format('Y/m') . '/' . $media->file_name;
                            $imageUrl = $currentHost . $relativePath;
                        }
                    @endphp
                    
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $media->file_name }}" 
                         class="max-w-full h-auto rounded border border-gray-200 mx-auto"
                         style="max-height: 400px;">
                @else
                    <div class="flex flex-col items-center justify-center p-12 bg-gray-50 rounded border-2 border-dashed border-gray-300">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm">{{ strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)) }} File</p>
                        <p class="text-gray-400 text-xs mt-1">{{ $media->mime_type }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información del archivo -->
        <div class="space-y-6">
            <!-- Información básica -->
            <div class="bg-white border border-gray-200 rounded p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información del Archivo</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre del archivo</dt>
                        <dd class="text-sm text-gray-900">{{ $media->file_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de archivo</dt>
                        <dd class="text-sm text-gray-900">{{ $media->mime_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tamaño</dt>
                        <dd class="text-sm text-gray-900">{{ number_format($media->size / 1024, 2) }} KB</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Colección</dt>
                        <dd class="text-sm text-gray-900">{{ ucfirst($media->collection_name) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de subida</dt>
                        <dd class="text-sm text-gray-900">{{ $media->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @php
                        $hasThumbInfo = false;
                        try {
                            $hasThumbInfo = $media->hasGeneratedConversion('thumb');
                        } catch (\Exception $e) {
                            $hasThumbInfo = false;
                        }
                    @endphp
                    @if($hasThumbInfo)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Miniatura disponible</dt>
                        <dd class="text-sm text-green-600">Sí</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- URLs -->
            <div class="bg-white border border-gray-200 rounded p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">URLs</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">URL Original</label>
                        <div class="flex">
                            @php
                                $originalUrl = null;
                                try {
                                    $originalUrl = $media->getUrl();
                                } catch (\Exception $e) {
                                    // Construir URL manualmente como fallback
                                    $currentHost = request()->getSchemeAndHttpHost();
                                    $relativePath = '/uploads/articles/' . $media->collection_name . '/' . $media->created_at->format('Y/m') . '/' . $media->file_name;
                                    $originalUrl = $currentHost . $relativePath;
                                }
                            @endphp
                            
                            @if($originalUrl)
                                <input type="text" value="{{ $originalUrl }}" readonly 
                                       class="flex-1 border border-gray-300 rounded-l px-3 py-2 text-sm bg-gray-50">
                                <button onclick="copyToClipboard('{{ $originalUrl }}')" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-r text-sm">
                                    Copiar
                                </button>
                            @else
                                <input type="text" value="Error: URL no disponible" readonly 
                                       class="flex-1 border border-red-300 rounded px-3 py-2 text-sm bg-red-50">
                            @endif
                        </div>
                    </div>
                    
                    @php
                        $hasThumb = false;
                        $thumbUrl = null;
                        try {
                            $hasThumb = $media->hasGeneratedConversion('thumb');
                            if ($hasThumb) {
                                $thumbUrl = $media->getUrl('thumb');
                            }
                        } catch (\Exception $e) {
                            $hasThumb = false;
                        }
                    @endphp
                    
                    @if($hasThumb && $thumbUrl)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">URL Miniatura</label>
                        <div class="flex">
                            <input type="text" value="{{ $thumbUrl }}" readonly 
                                   class="flex-1 border border-gray-300 rounded-l px-3 py-2 text-sm bg-gray-50">
                            <button onclick="copyToClipboard('{{ $thumbUrl }}')" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-r text-sm">
                                Copiar
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Propiedades personalizadas -->
            <div class="bg-white border border-gray-200 rounded p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Propiedades</h2>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Texto alternativo</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $media->getCustomProperty('alt_text') ?: 'No definido' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="text-sm text-gray-900">
                            {{ $media->getCustomProperty('description') ?: 'No definida' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Acciones -->
            <div class="bg-white border border-gray-200 rounded p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h2>
                
                <div class="flex space-x-3">
                    @php
                        $downloadUrl = null;
                        try {
                            $downloadUrl = $media->getUrl();
                        } catch (\Exception $e) {
                            // Construir URL manualmente como fallback
                            $currentHost = request()->getSchemeAndHttpHost();
                            $relativePath = '/uploads/articles/' . $media->collection_name . '/' . $media->created_at->format('Y/m') . '/' . $media->file_name;
                            $downloadUrl = $currentHost . $relativePath;
                        }
                    @endphp
                    
                    @if($downloadUrl)
                        <a href="{{ $downloadUrl }}" download 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                            Descargar
                        </a>
                    @else
                        <span class="bg-gray-400 text-white px-4 py-2 rounded text-sm">Descarga no disponible</span>
                    @endif
                    <button onclick="deleteFile({{ $media->id }})" 
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('URL copiada al portapapeles');
    }, function(err) {
        console.error('Error al copiar: ', err);
    });
}

async function deleteFile(id) {
    if (!confirm('¿Estás seguro de que quieres eliminar este archivo?')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ url('/admin/media') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            window.location.href = '{{ route("admin.media.index") }}';
        } else {
            alert('Error al eliminar archivo: ' + result.message);
        }
    } catch (error) {
        alert('Error al eliminar archivo: ' + error.message);
    }
}
</script>
@endsection
