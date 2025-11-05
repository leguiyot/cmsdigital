<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Digital - Noticias y Actualidad</title>
    <meta name="description" content="Tu fuente confiable de noticias y actualidad. Mantente informado con las últimas noticias, deportes, política y más.">
    
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
    
    <!-- Simple Icons for X (Twitter) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/x.svg">
    
    <style>
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        .breaking-news {
            animation: pulse 2s infinite;
        }
        /* Custom X icon */
        .icon-x {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg role='img' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Ctitle%3EX%3C/title%3E%3Cpath fill='%23000000' d='M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transition: transform 0.3s ease;
        }
        
        /* Custom Facebook icon */
        .icon-facebook {
            display: inline-block;
            width: 22px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%231877f2' d='M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 3.667h-3.533v7.98H9.101z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transition: transform 0.3s ease;
        }
        
        /* Social icons container */
        .social-icons {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }
        
        /* Custom Twitter icon */
        .icon-twitter {
            display: inline-block;
            width: 18px;
            height: 18px;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%231da1f2' d='M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            

            <!-- Main Header -->
            <div class="py-6">
                <div class="flex items-center justify-between mb-3 md:mb-1">
                    <!-- Social Media Icons - Desktop -->
                    <div class="flex-1 hidden md:flex items-center">
                        <div class="social-icons">
                            <a href="#" class="social-icon hover:scale-125">
                                <span class="icon-facebook"></span>
                            </a>
                            <a href="#" class="social-icon hover:scale-125">
                                <i class="fab fa-instagram text-pink-500" style="font-size: 20px;"></i>
                            </a>
                            <a href="#" class="social-icon hover:scale-125">
                                <span class="icon-x"></span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Logo Principal -->
                    <div class="flex items-center flex-1 md:flex-none justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 md:h-32 lg:h-36 w-auto">
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

                <!-- Social Media Icons - Mobile (below logo) -->
                <div class="md:hidden flex justify-center mb-4">
                    <div class="social-icons">
                        <a href="#" class="social-icon hover:scale-125">
                            <span class="icon-facebook"></span>
                        </a>
                        <a href="#" class="social-icon hover:scale-125">
                            <i class="fab fa-instagram text-pink-500" style="font-size: 20px;"></i>
                        </a>
                        <a href="#" class="social-icon hover:scale-125">
                            <span class="icon-x"></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="hidden md:flex items-center justify-center space-x-8 flex-1">
                        @foreach($sections->whereNull('parent_id')->take(8) as $section)
                            @php
                                $hasSubsections = $sections->where('parent_id', $section->id)->count() > 0;
                            @endphp
                            
                            @if($hasSubsections)
                                <!-- Sección con submenu -->
                                <div class="relative group">
                                    <a href="{{ route('sections.show', $section->slug) }}" 
                                       class="relative text-gray-700 group transition-colors duration-300 flex items-center">
                                        {{ $section->name }}
                                        <i class="fas fa-chevron-down ml-1 text-xs text-gray-500 group-hover:text-blue-600 transition-colors"></i>
                                        <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-900 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                                    </a>
                                    
                                    <!-- Submenu dropdown -->
                                    <div class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                        <div class="py-2">
                                            @foreach($sections->where('parent_id', $section->id) as $subsection)
                                                <a href="{{ route('sections.show', $subsection->slug) }}" 
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                                    {{ $subsection->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Sección normal sin submenu -->
                                <a href="{{ route('sections.show', $section->slug) }}" 
                                   class="relative text-gray-700 group transition-colors duration-300">
                                    {{ $section->name }}
                                    <span class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-900 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden mx-auto" x-data x-on:click="$dispatch('toggle-mobile-menu')">
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
                <img src="{{ $featuredArticles->first()->getFeaturedImageUrl() ?: 'https://via.placeholder.com/1200x600?text=Noticia+Principal' }}" 
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
                            <span>{{ $featuredArticles->first()->published_at->format('d/m/Y H:i') }}</span>
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
                        <img src="{{ $article->getFeaturedImageUrl() ?: 'https://via.placeholder.com/400x250?text=Noticia' }}" 
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
                            <span>{{ $article->published_at->format('d/m/Y H:i') }}</span>
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
                                <img src="{{ $article->getFeaturedImageUrl() ?: 'https://via.placeholder.com/200x150?text=Noticia' }}" 
                                     alt="{{ $article->title }}"
                                     class="w-full h-32 object-cover">
                            </div>
                            <div class="w-2/3 p-4">
                                <div class="flex items-center mb-2">
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium mr-2">
                                        {{ $article->section->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $article->published_at->format('d/m/Y H:i') }}
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
                                        {{ $article->views_count }} lecturas • {{ $article->published_at->format('d/m/Y H:i') }}
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
                                    {{ $article->published_at->format('d/m/Y H:i') }} • {{ $article->author->name }}
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
                        @auth
                        <li><a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Panel Admin</a></li>
                        @else
                        <li><a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Iniciar Sesión</a></li>
                        @endauth
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
                @foreach($sections->whereNull('parent_id')->sortBy('order') as $section)
                    @php
                        $hasSubsections = $sections->where('parent_id', $section->id)->count() > 0;
                    @endphp
                    
                    <div>
                        <a href="{{ route('sections.show', $section->slug) }}" 
                           class="block py-2 text-gray-700 hover:text-blue-600 {{ $hasSubsections ? 'font-medium' : '' }}">
                            {{ $section->name }}
                            @if($hasSubsections)
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            @endif
                        </a>
                        
                        @if($hasSubsections)
                            <div class="ml-4 mt-2 space-y-2">
                                @foreach($sections->where('parent_id', $section->id) as $subsection)
                                    <a href="{{ route('sections.show', $subsection->slug) }}" 
                                       class="block py-1 text-sm text-gray-600 hover:text-blue-600">
                                        • {{ $subsection->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </nav>
        </div>
    </div>
</body>
</html>
