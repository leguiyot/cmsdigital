@extends('layouts.admin')

@section('title', 'Gestión de Artículos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Gestión de Artículos</h1>
    <a href="{{ route('admin.articles.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Nuevo Artículo
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-64">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}" 
                   placeholder="Título, contenido o autor..." 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="min-w-48">
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select id="status" name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>En revisión</option>
            </select>
        </div>
        <div class="min-w-48">
            <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
            <select id="section" name="section" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Todas las secciones</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                Filtrar
            </button>
            <a href="{{ route('admin.articles.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Articles Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Artículo
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Autor
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sección
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($articles as $article)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($article->is_featured)
                                    <svg class="h-4 w-4 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ Str::limit($article->title, 60) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $article->views_count }} vistas
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $article->author->name ?? 'Sin autor' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $article->section->name ?? 'Sin sección' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($article->status === 'published') bg-green-100 text-green-800
                                @elseif($article->status === 'draft') bg-yellow-100 text-yellow-800
                                @elseif($article->status === 'review') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($article->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($article->status === 'published' && $article->published_at)
                                {{ $article->published_at->format('d/m/Y') }}
                            @else
                                {{ $article->created_at->format('d/m/Y') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($article->status === 'published')
                                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" 
                                       class="text-green-600 hover:text-green-900">Ver</a>
                                @endif
                                <a href="{{ route('admin.articles.edit', $article) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" 
                                      class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este artículo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay artículos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo artículo.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.articles.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nuevo Artículo
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articles->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection
