{{--
Layout principal del panel de administración

Este layout constituye la estructura base para todas las páginas del área administrativa.
Incluye:
- Sidebar de navegación con menús organizados por funcionalidad
- Header con información del usuario y notificaciones
- Área de contenido principal que se rellena con @yield('content')
- Scripts y estilos compartidos

Funcionalidades:
- Navegación colapsible con Alpine.js
- Indicadores de sección activa
- Responsive design para móviles
- CSRF token para formularios
- Stack de estilos y scripts adicionales
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> Panel de Administración</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Google Fonts - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')

    <style>
        .sidebar-active {
            @apply bg-blue-600 text-white;
        }

        .sidebar-inactive {
            @apply text-gray-600 hover:bg-gray-100 hover:text-gray-900;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="flex flex-col w-64 bg-white border-r border-gray-200">
            <div class="flex items-center justify-center h-20 px-4 bg-white border-b border-gray-200">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 md:h-28 lg:h-32 w-auto">

                </div>
            </div>

            <nav class="flex-1 px-2 py-4 space-y-2">
                <!-- Panel de Control -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Panel de Control
                </a>

                <!-- Artículos -->
                <div x-data="{ open: {{ request()->routeIs('admin.articles.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center w-full px-4 py-2 text-sm font-medium text-left rounded-md sidebar-inactive">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Artículos
                        <svg class="ml-auto h-4 w-4 transform transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 ml-8 space-y-1">
                        <a href="{{ route('admin.articles.index') }}"
                            class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.articles.index') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            Todos los Artículos
                        </a>
                        <a href="{{ route('admin.articles.create') }}"
                            class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.articles.create') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            Nuevo Artículo
                        </a>
                    </div>
                </div>

                <!-- Galería de Medios -->
                <a href="{{ route('admin.media.index') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.media.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Galería de Medios
                </a>

                <!-- Secciones -->
                <div x-data="{ open: {{ request()->routeIs('admin.sections.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="flex items-center w-full px-4 py-2 text-sm font-medium text-left rounded-md sidebar-inactive">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Secciones
                        <svg class="ml-auto h-4 w-4 transform transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" class="mt-2 ml-8 space-y-1">
                        <a href="{{ route('admin.sections.index') }}"
                            class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.sections.index') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            Todas las Secciones
                        </a>
                        <a href="{{ route('admin.sections.create') }}"
                            class="block px-4 py-2 text-sm rounded-md {{ request()->routeIs('admin.sections.create') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            Nueva Sección
                        </a>
                    </div>
                </div>

                <!-- Usuarios -->
                @can('manage users')
                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md sidebar-inactive">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        Usuarios
                    </a>
                @endcan

                <!-- Comentarios -->
                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md sidebar-inactive">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    Comentarios
                    @if(isset($pendingCommentsCount) && $pendingCommentsCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $pendingCommentsCount }}
                        </span>
                    @endif
                </a>

                <!-- Configuración -->
                @can('manage settings')
                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded-md sidebar-inactive">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configuración
                    </a>
                @endcan
            </nav>

            <!-- User Profile -->
            <div class="px-2 py-4 border-t border-gray-200">
                <div class="flex items-center px-4 py-2">
                    <img class="h-8 w-8 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff"
                        alt="{{ auth()->user()->name }}">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->position ?? 'Usuario' }}</p>
                    </div>
                </div>
                <div class="mt-2 px-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-2 py-1 text-sm text-gray-600 hover:text-gray-900 rounded-md hover:bg-gray-100">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h2 class="text-xl font-semibold text-gray-800">
                                @yield('title', 'Panel de Administración')
                            </h2>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('home') }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md sidebar-inactive">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                <span>Ver Sitio Web</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>