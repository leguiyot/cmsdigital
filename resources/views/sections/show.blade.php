{{-- 
    Vista de sección específica del diario digital
    
    Esta vista muestra todos los artículos de una sección específica.
    Incluye:
    - Header con información de la sección
    - Lista de artículos paginados
    - Navegación por secciones relacionadas
    - Sidebar con artículos relacionados y más leídos
    
    Datos recibidos del SectionController:
    - $section: Información de la sección actual
    - $articles: Artículos publicados de la sección (paginados)
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $section->seo_title ?: $section->name }} - Ndi</title>
    <meta name="description" content="{{ $section->meta_description ?: $section->description }}">
    
    @if($section->meta_keywords)
    <meta name="keywords" content="{{ is_array($section->meta_keywords) ? implode(', ', $section->meta_keywords) : $section->meta_keywords }}">
    @endif
    
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Montserrat', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <!-- Main Header -->
            <div class="py-3">
                <div class="flex items-center justify-between mb-2 md:mb-1">
                    <!-- Navigation Link - Desktop -->
                    <div class="flex-1 hidden md:flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Inicio
                        </a>
                    </div>
                    
                    <!-- Logo Principal -->
                    <div class="flex items-center flex-1 md:flex-none justify-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo CMS Digital" class="h-24 md:h-28 lg:h-32 w-auto">
                        </a>
                    </div>
                    
                    <!-- Search Button - Desktop only -->
                    <div class="flex-1 hidden md:flex justify-end relative">
                        <div class="flex items-center space-x-4" x-data="{ searchOpen: false }">
                            <!-- Search Toggle Button -->
                            <button @click="searchOpen = !searchOpen" 
                                    class="p-2 rounded-full hover:bg-gray-100 transition-all duration-300 hover:shadow-md">
                                <i class="fas fa-search text-gray-700 text-lg hover:text-blue-800 transition-colors"></i>
                            </button>
                            
                            <!-- Search Input (expandable to the left) -->
                            <div x-show="searchOpen" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95 translate-x-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 scale-95 translate-x-4"
                                 class="absolute right-12 top-0 z-10 bg-white shadow-xl rounded-full border border-gray-200">
                                <div class="relative">
                                    <input type="text" placeholder="Buscar noticias..." 
                                           x-ref="searchInput"
                                           @click.away="searchOpen = false"
                                           @keydown.escape="searchOpen = false"
                                           class="w-72 pl-12 pr-4 py-2.5 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent">
                                    <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation - Link to Home -->
                <div class="md:hidden flex justify-center mb-2">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-2 border-t border-gray-200">
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $section->name }}</h1>
                       
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @if($articles->count() > 0)
            <!-- Artículos de la Sección -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista Principal de Artículos -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach($articles as $article)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="md:flex">
                                <!-- Imagen del artículo -->
                                <div class="md:w-2/5">
                                    @if($article->getFirstMediaUrl('cover'))
                                        <img src="{{ $article->getFirstMediaUrl('cover', 'medium') }}" 
                                             alt="{{ $article->title }}"
                                             class="w-full h-56 md:h-full object-cover">
                                    @else
                                        <div class="w-full h-56 md:h-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Contenido del artículo -->
                                <div class="md:w-3/5 p-6">
                                    <!-- Volanta -->
                                    @if($article->volanta)
                                    <div class="mb-2">
                                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            {{ $article->volanta }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <!-- Título -->
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-tight">
                                        <a href="{{ route('articles.show', $article->slug) }}" 
                                           class="hover:text-blue-600 transition-colors">
                                            {{ $article->title }}
                                        </a>
                                    </h2>
                                    
                                    <!-- Extracto -->
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ $article->excerpt }}
                                    </p>
                                    
                                    <!-- Metadatos -->
                                    <div class="flex items-center mb-4 text-sm text-gray-500">
                                        <span>{{ $article->published_at->format('d/m/Y H:i') }}</span>
                                        <span class="mx-2">•</span>
                                        <span>Por {{ $article->author->name }}</span>
                                        @if($article->reading_time)
                                            <span class="mx-2">•</span>
                                            <span>{{ $article->reading_time }} min de lectura</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Tags -->
                                    @if($article->tags && count($article->tags) > 0)
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach(array_slice($article->tags, 0, 3) as $tag)
                                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <!-- Botón Leer Más -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('articles.show', $article->slug) }}" 
                                           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                            Leer más
                                            <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                        </a>
                                        
                                       
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                

                    <!-- Más Artículos -->
                    @php
                        $moreArticles = \App\Models\Article::where('section_id', $section->id)
                            ->where('status', 'published')
                            ->whereNotIn('id', $articles->pluck('id'))
                            ->orderBy('views_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($moreArticles->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-fire text-red-500 mr-2"></i>
                            Más en {{ $section->name }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($moreArticles as $index => $moreArticle)
                            <div class="flex items-start space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-gray-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm leading-tight mb-1">
                                        <a href="{{ route('articles.show', $moreArticle->slug) }}" 
                                           class="text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ Str::limit($moreArticle->title, 80) }}
                                        </a>
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        {{ $moreArticle->views_count }} lecturas • {{ $moreArticle->published_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Paginación -->
            @if($articles->hasPages())
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            @endif
        @else
            <!-- Estado vacío -->
            <div class="text-center py-12">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay artículos en esta sección</h3>
                    <p class="text-gray-500 mb-6">
                        Aún no se han publicado artículos en la sección "{{ $section->name }}". 
                        Te invitamos a explorar otras secciones.
                    </p>
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Inicio
                    </a>
                </div>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto mr-3">
                        <h3 class="text-xl font-bold">CMS Digital</h3>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        Tu fuente confiable de noticias y actualidad. Mantente informado con contenido de calidad.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="hover:opacity-80 transition-opacity">
                            <img src="{{ asset('images/x-logo.jpg') }}" alt="X (Twitter)" class="w-5 h-5 object-contain filter brightness-0 invert opacity-60 hover:opacity-100 transition-opacity">
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://wa.me/34900123456" target="_blank" class="text-gray-400 hover:text-green-400 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Secciones</h4>
                    <ul class="space-y-2 text-sm">
                        @php
                            $footerSections = \App\Models\Section::where('is_active', true)->orderBy('name')->take(5)->get();
                        @endphp
                        @foreach($footerSections as $sectionItem)
                        <li>
                            <a href="{{ route('sections.show', $sectionItem->slug) }}" 
                               class="text-gray-300 hover:text-white transition-colors">
                                {{ $sectionItem->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Acerca de</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contacto</a></li>
                        @auth
                        <li><a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Panel Admin</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Iniciar Sesión</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} CMS Digital. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>
