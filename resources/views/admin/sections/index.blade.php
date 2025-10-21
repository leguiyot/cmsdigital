@extends('layouts.admin')

@section('title', 'Gestión de Secciones')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Gestión de Secciones</h1>
    <a href="{{ route('admin.sections.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
        + Nueva Sección
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow">
    <div class="p-6">
        <!-- Filters -->
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Buscar secciones..." 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                
                <div>
                    <select name="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="name" {{ request('sort', 'name') === 'name' ? 'selected' : '' }}>Nombre</option>
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Fecha de creación</option>
                        <option value="articles_count" {{ request('sort') === 'articles_count' ? 'selected' : '' }}>Artículos</option>
                    </select>
                </div>
                
                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.sections.index') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-center">
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        <!-- Sections Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sección
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Descripción
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Artículos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Creado
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sections as $section)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-{{ $section->is_active ? 'blue' : 'gray' }}-100 flex items-center justify-center">
                                            <span class="text-{{ $section->is_active ? 'blue' : 'gray' }}-600 font-medium text-sm">
                                                {{ strtoupper(substr($section->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $section->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $section->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ Str::limit($section->description ?? 'Sin descripción', 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $section->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $section->articles_count }} artículos
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $section->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.sections.edit', $section) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" 
                                          class="inline" 
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta sección?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-1">No hay secciones</p>
                                    <p class="text-gray-500 mb-4">Comienza creando tu primera sección para organizar el contenido.</p>
                                    <a href="{{ route('admin.sections.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                        Crear Primera Sección
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sections->hasPages())
            <div class="mt-6">
                {{ $sections->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@if($sections->count() > 0)
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Estadísticas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Total de secciones:</span>
                <span class="text-sm font-medium">{{ $sections->total() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Activas:</span>
                <span class="text-sm font-medium text-green-600">{{ $sections->where('is_active', true)->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-sm text-gray-600">Inactivas:</span>
                <span class="text-sm font-medium text-red-600">{{ $sections->where('is_active', false)->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.sections.create') }}" 
               class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-md">
                Nueva Sección
            </a>
            <a href="{{ route('admin.articles.index') }}" 
               class="block w-full bg-gray-600 hover:bg-gray-700 text-white text-center py-2 px-4 rounded-md">
                Ver Artículos
            </a>
        </div>
    </div>

    <!-- Información -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Información</h3>
        <div class="text-sm text-gray-600 space-y-2">
            <p>Las secciones organizan el contenido de tu sitio web.</p>
            <p>Cada artículo debe estar asignado a una sección.</p>
            <p>Las secciones inactivas no aparecen en el sitio público.</p>
        </div>
    </div>
</div>
@endif
@endsection
