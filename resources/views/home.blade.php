<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Digital - Noticias y Actualidad</title>
    <meta name="description" content="Tu fuente confiable de noticias y actualidad. Mantente informado con las últimas noticias, deportes, política y más.">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        .breaking-news {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <!-- Top Bar -->
            <div class="border-b border-gray-200 py-2">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">{{ now()->format('l, j \d\e F \d\e Y') }}</span>
                        <span class="text-red-600 font-semibold">
                            <i class="fas fa-thermometer-half mr-1"></i>
                            22°C Madrid
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#" class="text-gray-600 hover:text-blue-600">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-blue-600">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="py-6">
                <div class="flex flex-col items-center justify-center text-center mb-6">
                    <!-- Logo Principal Centrado -->
                    <div class="mb-4">
                        <img src="{{ asset('images/mdi-logo.svg') }}" alt="MDI Logo" class="h-16 md:h-20 lg:h-24 w-auto mx-auto">
                    </div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-blue-900 mb-2">
                        CMS Digital
                    </h1>
                    <p class="text-sm md:text-base text-gray-500">Noticias y actualidad</p>
                </div>

                <!-- Navegación y búsqueda -->
                <div class="flex justify-between items-center">
                    <div class="flex-1"></div>
                    
                    <!-- Search -->
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Buscar noticias..." 
                                   class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        @auth
                            <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Panel Admin
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Iniciar Sesión</a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="font-semibold text-blue-600 hover:text-blue-800">
                            <i class="fas fa-home mr-1"></i>
                            Inicio
                        </a>
                        @foreach($sections->take(6) as $section)
                            <a href="{{ route('sections.show', $section->slug) }}" 
                               class="text-gray-700 hover:text-blue-600 transition-colors">
                                {{ $section->name }}
                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden" x-data x-on:click="$dispatch('toggle-mobile-menu')">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        
        <!-- 1. BLOQUE PRINCIPAL: Artículo Destacado (Máxima Importancia) -->
        @if($featuredArticles->isNotEmpty())
        <section class="mb-8">
            <div class="relative h-96 md:h-[500px] rounded-xl overflow-hidden shadow-2xl">
                <img src="{{ $featuredArticles->first()->featured_image ?? 'https://via.placeholder.com/1200x600?text=Noticia+Principal' }}" 
                     alt="{{ $featuredArticles->first()->title }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 gradient-overlay"></div>
                <div class="absolute inset-0 flex items-end">
                    <div class="p-8 text-white">
                        <div class="flex items-center mb-2">
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold mr-3 breaking-news">
                                <i class="fas fa-star mr-1"></i>
                                DESTACADO
                            </span>
                            <span class="text-blue-200">{{ $featuredArticles->first()->section->name }}</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">
                            <a href="{{ route('articles.show', $featuredArticles->first()->slug) }}" 
                               class="hover:text-blue-200 transition-colors">
                                {{ $featuredArticles->first()->title }}
                            </a>
                        </h1>
                        <p class="text-lg text-gray-200 mb-4 max-w-3xl">
                            {{ $featuredArticles->first()->excerpt }}
                        </p>
                        <div class="flex items-center text-sm text-gray-300">
                            <span>Por {{ $featuredArticles->first()->author->name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $featuredArticles->first()->published_at->diffForHumans() }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $featuredArticles->first()->reading_time }} min lectura</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- 2. BLOQUE SECUNDARIO: Noticias Destacadas (Alta Importancia) -->
        @if($featuredArticles->count() > 1)
        <section class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-fire text-red-500 mr-2"></i>
                    Noticias Destacadas
                </h2>
                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Ver todas →</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredArticles->skip(1)->take(4) as $article)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="relative">
                        <img src="{{ $article->featured_image ?? 'https://via.placeholder.com/400x250?text=Noticia' }}" 
                             alt="{{ $article->title }}"
                             class="w-full h-48 object-cover">
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                {{ $article->section->name }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 leading-tight">
                            <a href="{{ route('articles.show', $article->slug) }}" 
                               class="text-gray-900 hover:text-blue-600 transition-colors">
                                {{ Str::limit($article->title, 80) }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-3">
                            {{ Str::limit($article->excerpt, 120) }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $article->author->name }}</span>
                            <span>{{ $article->published_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif

        <!-- 3. BLOQUE DE CONTENIDO ORGANIZADO: Por Secciones (Media-Alta Importancia) -->
        <section class="mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Columna Principal: Últimas Noticias -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            Últimas Noticias
                        </h2>
                    </div>
                    
                    <div class="space-y-6">
                        @foreach($latestNews->take(6) as $article)
                        <article class="flex bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="w-1/3">
                                <img src="{{ $article->featured_image ?? 'https://via.placeholder.com/200x150?text=Noticia' }}" 
                                     alt="{{ $article->title }}"
                                     class="w-full h-32 object-cover">
                            </div>
                            <div class="w-2/3 p-4">
                                <div class="flex items-center mb-2">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium mr-2">
                                        {{ $article->section->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $article->published_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-lg mb-2 leading-tight">
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ Str::limit($article->title, 100) }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">
                                    {{ Str::limit($article->excerpt, 150) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>Por {{ $article->author->name }}</span>
                                    <span>{{ $article->reading_time }} min lectura</span>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar: Más Leídos y Trending -->
                <div class="space-y-8">
                    
                    <!-- Más Leídos -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-chart-line text-green-500 mr-2"></i>
                            Más Leídos
                        </h3>
                        <div class="space-y-4">
                            @foreach($mostRead->take(5) as $index => $article)
                            <div class="flex items-start space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1">
                                    <h4 class="font-medium text-sm leading-tight mb-1">
                                        <a href="{{ route('articles.show', $article->slug) }}" 
                                           class="text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ Str::limit($article->title, 80) }}
                                        </a>
                                    </h4>
                                    <div class="text-xs text-gray-500">
                                        {{ $article->views_count }} lecturas • {{ $article->published_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-lg shadow-md p-6 text-white">
                        <h3 class="text-xl font-bold mb-2">
                            <i class="fas fa-envelope mr-2"></i>
                            Mantente Informado
                        </h3>
                        <p class="text-blue-100 mb-4 text-sm">
                            Recibe las noticias más importantes directamente en tu email.
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Tu email" 
                                   class="w-full px-3 py-2 rounded text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-white">
                            <button type="submit" 
                                    class="w-full bg-white text-blue-600 py-2 rounded font-semibold text-sm hover:bg-gray-100 transition-colors">
                                Suscribirse Gratis
                            </button>
                        </form>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            <i class="fas fa-share-alt text-purple-500 mr-2"></i>
                            Síguenos
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="#" class="flex items-center justify-center py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f mr-2"></i>
                                <span class="text-sm font-medium">Facebook</span>
                            </a>
                            <a href="#" class="flex items-center justify-center py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors">
                                <i class="fab fa-twitter mr-2"></i>
                                <span class="text-sm font-medium">Twitter</span>
                            </a>
                            <a href="#" class="flex items-center justify-center py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                                <i class="fab fa-instagram mr-2"></i>
                                <span class="text-sm font-medium">Instagram</span>
                            </a>
                            <a href="#" class="flex items-center justify-center py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fab fa-youtube mr-2"></i>
                                <span class="text-sm font-medium">YouTube</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. BLOQUE POR SECCIONES: Contenido Categorizado (Media Importancia) -->
        @if($latestBySection && count($latestBySection) > 0)
        <section class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestBySection as $sectionSlug => $articles)
                    @if($articles->isNotEmpty())
                    @php $section = $sections->where('slug', $sectionSlug)->first(); @endphp
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4">
                            <h3 class="text-white font-bold text-lg">
                                <i class="fas fa-folder-open mr-2"></i>
                                {{ $section->name }}
                            </h3>
                        </div>
                        <div class="p-4 space-y-4">
                            @foreach($articles->take(3) as $article)
                            <article class="border-b border-gray-100 pb-3 last:border-b-0">
                                <h4 class="font-medium text-sm leading-tight mb-2">
                                    <a href="{{ route('articles.show', $article->slug) }}" 
                                       class="text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ Str::limit($article->title, 90) }}
                                    </a>
                                </h4>
                                <div class="text-xs text-gray-500">
                                    {{ $article->published_at->diffForHumans() }} • {{ $article->author->name }}
                                </div>
                            </article>
                            @endforeach
                        </div>
                        <div class="px-4 pb-4">
                            <a href="{{ route('sections.show', $section->slug) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todas las noticias de {{ $section->name }} →
                            </a>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/mdi-logo.svg') }}" alt="MDI Logo" class="h-8 w-auto mr-3">
                        <h3 class="text-xl font-bold">CMS Digital</h3>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        Tu fuente confiable de noticias y actualidad. Mantente informado con contenido de calidad.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Secciones</h4>
                    <ul class="space-y-2 text-sm">
                        @foreach($sections->take(5) as $section)
                        <li>
                            <a href="{{ route('sections.show', $section->slug) }}" 
                               class="text-gray-300 hover:text-white transition-colors">
                                {{ $section->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Empresa</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="text-gray-300 hover:text-white">Acerca de</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contacto</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Publicidad</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Términos de Uso</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Privacidad</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Contacto</h4>
                    <div class="text-sm text-gray-300 space-y-2">
                        <p><i class="fas fa-envelope mr-2"></i> contacto@cmsdigital.com</p>
                        <p><i class="fas fa-phone mr-2"></i> +34 900 123 456</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Madrid, España</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} CMS Digital. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div x-data="{ open: false }" x-on:toggle-mobile-menu.window="open = !open" 
         x-show="open" x-transition class="fixed inset-0 z-50 md:hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" x-on:click="open = false"></div>
        <div class="fixed right-0 top-0 h-full w-64 bg-white shadow-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg">Menú</h3>
                <button x-on:click="open = false" class="text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="space-y-4">
                <a href="{{ route('home') }}" class="block py-2 text-blue-600 font-semibold">Inicio</a>
                @foreach($sections as $section)
                    <a href="{{ route('sections.show', $section->slug) }}" 
                       class="block py-2 text-gray-700 hover:text-blue-600">
                        {{ $section->name }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</body>
</html>
