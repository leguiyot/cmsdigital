@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Articles -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Artículos</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_articles'] }}</p>
            </div>
        </div>
    </div>

    <!-- Published Articles -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Publicados</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['published_articles'] }}</p>
            </div>
        </div>
    </div>

    <!-- Draft Articles -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Borradores</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['draft_articles'] }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Comments -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Comentarios Pendientes</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_comments'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Articles -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Artículos Recientes</h3>
        </div>
        <div class="p-6">
            @if($recentArticles->count() > 0)
                <div class="space-y-4">
                    @foreach($recentArticles as $article)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $article->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    por {{ $article->author->name ?? 'Usuario' }} • {{ $article->created_at->diffForHumans() }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Editar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    <a href="{{ route('admin.articles.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        Ver todos los artículos →
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No hay artículos recientes</p>
            @endif
        </div>
    </div>

    <!-- Top Articles -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Artículos Más Leídos</h3>
        </div>
        <div class="p-6">
            @if($topArticles->count() > 0)
                <div class="space-y-4">
                    @foreach($topArticles as $article)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900 truncate">{{ $article->title }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $article->views_count }} vistas • {{ $article->section->name ?? 'Sin sección' }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    Ver
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No hay estadísticas disponibles</p>
            @endif
        </div>
    </div>
</div>

@if(isset($pendingComments) && $pendingComments->count() > 0)
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Comentarios Pendientes de Moderación</h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @foreach($pendingComments as $comment)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">{{ Str::limit($comment->body, 150) }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                por {{ $comment->author_name }} en 
                                <a href="{{ route('articles.show', $comment->article->slug) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    {{ $comment->article->title }}
                                </a>
                                • {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="ml-4 flex space-x-2">
                            <button class="text-green-600 hover:text-green-900 text-sm">Aprobar</button>
                            <button class="text-red-600 hover:text-red-900 text-sm">Rechazar</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="mx-auto h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Nuevo Artículo</h3>
        <p class="text-gray-500 mb-4">Crear un nuevo artículo o noticia</p>
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Crear Artículo
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="mx-auto h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Nueva Sección</h3>
        <p class="text-gray-500 mb-4">Organizar contenido en secciones</p>
        <a href="{{ route('admin.sections.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            Crear Sección
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6 text-center">
        <div class="mx-auto h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Estadísticas</h3>
        <p class="text-gray-500 mb-4">Ver análisis detallados</p>
        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
            Ver Estadísticas
        </button>
    </div>
</div>
@endsection
