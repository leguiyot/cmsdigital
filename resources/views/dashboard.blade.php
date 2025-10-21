<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - CMS Digital') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Total Artículos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_articles'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Publicados</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['published_articles'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.966-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Borradores</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['draft_articles'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 truncate">Comentarios Pendientes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_comments'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido Principal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Artículos Recientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Artículos Recientes</h3>
                        <div class="space-y-4">
                            @forelse($recentArticles ?? [] as $article)
                                <div class="flex items-center justify-between p-3 border rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($article->title, 50) }}</h4>
                                        <p class="text-xs text-gray-500">
                                            Por {{ $article->author->name ?? 'Sin autor' }} • 
                                            {{ $article->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($article->status === 'published') bg-green-100 text-green-800
                                        @elseif($article->status === 'draft') bg-yellow-100 text-yellow-800
                                        @elseif($article->status === 'review') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No hay artículos recientes.</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.articles.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todos los artículos →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.articles.create') }}" 
                               class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                                + Nuevo Artículo
                            </a>
                            <a href="{{ route('admin.sections.index') }}" 
                               class="block w-full bg-gray-600 text-white text-center py-2 px-4 rounded-md hover:bg-gray-700 transition-colors">
                                Gestionar Secciones
                            </a>
                            <a href="{{ route('home') }}" target="_blank"
                               class="block w-full bg-green-600 text-white text-center py-2 px-4 rounded-md hover:bg-green-700 transition-colors">
                                Ver Sitio Web
                            </a>
                        </div>

                        @if(isset($pendingComments) && $pendingComments->count() > 0)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Comentarios Pendientes</h4>
                            <div class="space-y-2">
                                @foreach($pendingComments->take(3) as $comment)
                                    <div class="p-2 border rounded text-xs">
                                        <p class="text-gray-600">{{ Str::limit($comment->body, 80) }}</p>
                                        <p class="text-gray-400 mt-1">
                                            En: {{ Str::limit($comment->article->title ?? 'Sin título', 30) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
