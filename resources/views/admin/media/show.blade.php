{{-- 
    Vista simple para mostrar solo la imagen
--}}
@extends('layouts.admin')

@section('title', 'Ver Imagen')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Header simple -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $media->file_name }}</h1>
        </div>
        <div>
            <a href="{{ route('admin.media.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm">
                ← Volver
            </a>
        </div>
    </div>

    <!-- Solo la imagen centrada -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="text-center">
            @if(str_starts_with($media->mime_type, 'image/'))
                @php
                    // Obtener URL de la imagen
                    try {
                        $imageUrl = $media->getUrl();
                    } catch (\Exception $e) {
                        // Fallback: construir URL manualmente
                        $currentHost = request()->getSchemeAndHttpHost();
                        $createdDate = $media->created_at ? $media->created_at->format('Y/m') : date('Y/m');
                        $relativePath = '/uploads/articles/' . $media->collection_name . '/' . $createdDate . '/' . $media->file_name;
                        $imageUrl = $currentHost . $relativePath;
                    }
                @endphp
                
                <img src="{{ $imageUrl }}" 
                     alt="{{ $media->file_name }}" 
                     class="max-w-full h-auto rounded border border-gray-200 mx-auto">
            @else
                <div class="flex flex-col items-center justify-center p-12 text-gray-500">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-lg">{{ strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)) }} File</p>
                    <p class="text-sm text-gray-400">{{ $media->mime_type }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
