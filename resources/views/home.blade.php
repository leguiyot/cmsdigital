{{--
Vista principal de
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
<meta name="msapplication-TileColor" content="#ffffff">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

<!-- Google Fonts - Montserrat -->rio digital (Página de inicio)

Esta vista constituye la portada del sitio web del diario digital.
Presenta los contenidos más relevantes organizados en diferentes secciones:
- Artículos destacados (carousel principal)
- Últimas noticias por sección
- Artículos más leídos
- Navegación por secciones

Datos recibidos del HomeController:
- $featuredArticles: Artículos marcados como destacados (ordenados por featured_at)
- $sections: Secciones activas con sus subsecciones
- $latestBySection: Últimos artículos agrupados por sección
- $mostRead: Artículos más leídos de la semana
- $latestNews: Lista general de noticias recientes
--}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ndi Diario Digital</title>
    <meta name="description"
        content="Tu fuente confiable de noticias y actualidad. Mantente informado con las últimas noticias, deportes, política y más.">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('favicon.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff" )>

    <!-- Google Fonts - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

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

    <style>
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        .breaking-news {
            animation: pulse 2s infinite;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header with auto-hide on scroll -->
    <header id="mainHeader"
        class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50 transition-transform duration-300 ease-in-out">
        <div class="container mx-auto px-4">


            <!-- Main Header -->
            <div class="py-3">
                <div class="flex items-center justify-between mb-2 md:mb-1">
                    <!-- Social Media Icons - Desktop -->
                    <div class="flex-1 hidden md:flex items-center">
                        <div class="flex items-center space-x-4">
                            <a href="#"
                                class="text-blue-600 hover:text-blue-700 transition-colors hover:scale-110 transform duration-200">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <a href="#"
                                class="text-pink-500 hover:text-pink-600 transition-colors hover:scale-110 transform duration-200">
                                <i class="fab fa-instagram text-xl"></i>
                            </a>
                            <a href="#"
                                class="text-gray-800 hover:text-gray-900 transition-colors hover:scale-110 transform duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Logo Principal -->
                    <div class="flex items-center flex-1 md:flex-none justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-24 md:h-28 lg:h-32 w-auto">
                    </div>

                    <!-- Search Button - Desktop only -->
                    <div class="flex-1 hidden md:flex justify-end relative">
                        <div class="flex items-center space-x-4" x-data="{ searchOpen: false }">
                            <!-- Search Toggle Button -->
                            <button @click="searchOpen = !searchOpen"
                                class="p-2 rounded-full hover:bg-gray-100 transition-all duration-300 hover:shadow-md">
                                <i
                                    class="fas fa-search text-gray-700 text-lg hover:text-blue-800 transition-colors"></i>
                            </button>

                            <!-- Search Input (expandable to the left) -->
                            <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-x-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-x-4"
                                class="absolute right-12 top-0 z-10 bg-white shadow-xl rounded-full border border-gray-200">
                                <div class="relative">
                                    <input type="text" placeholder="Buscar noticias..." x-ref="searchInput"
                                        @click.away="searchOpen = false" @keydown.escape="searchOpen = false"
                                        class="w-72 pl-12 pr-4 py-2.5 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 bg-transparent">
                                    <i class="fas fa-search absolute left-4 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Icons - Mobile (below logo) -->
                <div class="md:hidden flex justify-center mb-2">
                    <div class="flex items-center space-x-4">
                        <a href="#"
                            class="text-blue-600 hover:text-blue-700 transition-colors hover:scale-110 transform duration-200">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#"
                            class="text-pink-500 hover:text-pink-600 transition-colors hover:scale-110 transform duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#"
                            class="text-gray-800 hover:text-gray-900 transition-colors hover:scale-110 transform duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="py-2 border-t border-gray-200">
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
                                        <i
                                            class="fas fa-chevron-down ml-1 text-xs text-gray-500 group-hover:text-blue-600 transition-colors"></i>
                                        <span
                                            class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-900 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                                    </a>

                                    <!-- Submenu dropdown -->
                                    <div
                                        class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
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
                                    <span
                                        class="absolute -bottom-1 left-0 w-full h-0.5 bg-blue-900 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
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

    <!-- Spacer to prevent content jump -->
    <div class="h-40 md:h-44 lg:h-48"></div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">

        <!-- 1. BLOQUE PRINCIPAL: Artículo Destacado (Máxima Importancia) -->
        @if($featuredArticles->isNotEmpty())
            <section class="mb-8">
                <div class="relative h-[450px] md:h-[600px] lg:h-[650px] rounded-xl overflow-hidden shadow-2xl">
                    <img src="{{ $featuredArticles->first()->getFeaturedImageUrl() ?: 'https://via.placeholder.com/1200x600?text=Noticia+Principal' }}"
                        alt="{{ $featuredArticles->first()->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 gradient-overlay"></div>
                    <div class="absolute inset-0 flex items-end">
                        <div class="p-8 text-white">
                            <div class="flex items-center mb-2">
                                <span
                                    class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold mr-3 breaking-news">
                                    <i class="fas fa-star mr-1"></i>
                                    DESTACADO
                                </span>
                                <span
                                    class="text-blue-200">{{ $featuredArticles->first()->volanta ?? $featuredArticles->first()->section->name }}</span>
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
                                <span>Por {{ $featuredArticles->first()->visible_author_name }}</span>
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredArticles->skip(1)->take(3) as $article)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="relative">
                                <img src="{{ $article->getFeaturedImageUrl() ?: 'https://via.placeholder.com/600x350?text=Noticia' }}"
                                    alt="{{ $article->title }}" class="w-full h-56 object-cover">
                                <div class="absolute top-3 left-3">
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                        {{ $article->volanta ?? $article->section->name }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-xl mb-2 leading-tight">
                                    <a href="{{ route('articles.show', $article->slug) }}"
                                        class="text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ Str::limit($article->title, 80) }}
                                    </a>
                                </h3>
                                <p class="text-gray-700 text-base mb-3">
                                    {{ Str::limit($article->excerpt, 140) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $article->visible_author_name }}</span>
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
                        @foreach($latestNews->take(4) as $article)
                            <article
                                class="flex bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="w-1/3">
                                    <img src="{{ $article->getFeaturedImageUrl() ?: 'https://via.placeholder.com/200x150?text=Noticia' }}"
                                        alt="{{ $article->title }}" class="w-full h-32 object-cover">
                                </div>
                                <div class="w-2/3 p-4">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium mr-2">
                                            {{ $article->volanta ?? $article->section->name }}
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
                                        <span>{{ $article->visible_author_name }}</span>
                                        <span>{{ $article->published_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar: Más Leídos y Trending -->
                <div class="lg:col-span-1"></div>
            </div>
        </section>

        <!-- 4. BLOQUE POR SECCIONES: Contenido Categorizado (Media Importancia) 
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
                                    {{ $article->published_at->format('d/m/Y H:i') }} • {{ $article->visible_author_name }}
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
        @endif-->

        <!-- SLIDER: Carrusel de Noticias -->
        <section class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-layer-group text-gray-700 mr-2"></i>
                    Más noticias
                </h2>
                <div class="text-sm text-gray-500">Desliza para ver más</div>
            </div>

            <div x-data="{
                     items: {{ $latestNews->skip(4)->take(8)->map(function ($a) {
    return [
        'title' => $a->title,
        'excerpt' => Str::limit($a->excerpt, 200),
        'slug' => $a->slug,
        'cover' => $a->getFeaturedImageUrl() ?? null,
        'published_at' => $a->published_at->toDateTimeString(),
        'show_author_name' => (bool) $a->show_author_name,
        'author' => $a->author ? ['name' => $a->author->name] : null,
        'section' => $a->section ? ['name' => $a->section->name] : null,
    ]; })->values()->toJson() }},
                     idx: 0,
                     timer: null,
                     resumeTimer: null,
                     restartDelay: 15000, // reanuda auto-advance tras 15s de inactividad
                     next() { if(this.items.length) this.idx = (this.idx + 1) % this.items.length },
                     prev() { if(this.items.length) this.idx = (this.idx - 1 + this.items.length) % this.items.length },
                     startAuto() {
                         if(this.timer) return;
                         this.timer = setInterval(() => { if(this.items.length) this.idx = (this.idx + 1) % this.items.length }, 10000)
                     },
                     stopAuto() {
                         if(this.timer) { clearInterval(this.timer); this.timer = null }
                         if(this.resumeTimer) { clearTimeout(this.resumeTimer); this.resumeTimer = null }
                     },
                     handleInteraction() {
                         // Pausar inmediatamente y reanudar después de restartDelay de inactividad
                         this.stopAuto();
                         this.resumeTimer = setTimeout(() => { this.startAuto() }, this.restartDelay);
                     }
                }" x-init="startAuto()" @mouseenter="stopAuto()" @mouseleave="startAuto()"
                class="relative px-4 md:px-8">

                <div class="relative h-56 md:h-72 lg:h-80 overflow-hidden">
                    <template x-for="(item, i) in items" :key="i">
                        <div class="absolute inset-0" x-show="i === idx"
                            x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 transform translate-x-6 scale-95"
                            x-transition:enter-end="opacity-100 transform translate-x-0 scale-100"
                            x-transition:leave="transition ease-in duration-500"
                            x-transition:leave-start="opacity-100 transform translate-x-0 scale-100"
                            x-transition:leave-end="opacity-0 transform -translate-x-6 scale-95">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden h-full">
                                <div class="md:flex h-full">
                                    <div class="md:w-1/3 h-full flex-shrink-0">
                                        <img :src="item.cover ? item.cover : 'https://via.placeholder.com/600x400?text=Noticia'"
                                            class="w-full h-full object-cover" :alt="item.title">
                                    </div>
                                    <div class="p-6 md:w-2/3 h-full overflow-hidden">
                                        <h3 class="font-bold text-xl mb-2 leading-tight" x-text="item.title"></h3>
                                        <p class="text-gray-700 text-base mb-3" x-text="item.excerpt"></p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span
                                                x-text="`Por ${item.show_author_name ? (item.author ? item.author.name : 'Ndi Diario Digital') : (item.section ? `Ndi Diario Digital - ${item.section.name}` : 'Ndi Diario Digital')}`"></span>
                                            <span class="mx-2">•</span>
                                            <span
                                                x-text="new Date(item.published_at).toLocaleString('es-AR', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })"></span>
                                        </div>
                                        <div class="mt-4">
                                            <a :href="`/articulos/${item.slug}`"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Leer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Controls -->
                <div class="absolute inset-y-1/2 -left-6 md:-left-8 transform -translate-y-1/2 z-50">
                    <button @click="prev(); handleInteraction()"
                        class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
                <div class="absolute inset-y-1/2 -right-6 md:-right-8 transform -translate-y-1/2 z-50">
                    <button @click="next(); handleInteraction()"
                        class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Dots -->
                <div class="flex justify-center mt-4">
                    <template x-for="(item, i) in items" :key="i">
                        <button @click="idx = i; handleInteraction()"
                            :class="{'bg-blue-600': i===idx, 'bg-gray-300': i!==idx }"
                            class="h-2 w-6 mx-1 rounded-full"></button>
                    </template>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto mr-3">

                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        Tu fuente confiable de noticias y actualidad. Mantente informado con contenido de calidad.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/34900123456" target="_blank"
                            class="text-gray-400 hover:text-green-400 transition-colors">
                            <i class="fab fa-whatsapp"></i>
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
                            <li><a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Panel de
                                    Control</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Iniciar Sesión</a>
                            </li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Contacto</h4>
                    <div class="text-sm text-gray-300 space-y-2">
                        <p><i class="fas fa-envelope mr-2"></i> contacto@cmsdigital.com</p>
                        <p><i class="fas fa-phone mr-2"></i> +34 900 123 456</p>
                        <p><i class="fas fa-map-marker-alt mr-2"></i> Mendoza , Argentina</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} By Mara Web Desing. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu -->
    <div x-data="{ open: false }" x-on:toggle-mobile-menu.window="open = !open" x-show="open" x-transition
        class="fixed inset-0 z-50 md:hidden">
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

    <!-- Auto-hide header script -->
    <script>
        // Auto-hide header on scroll
        let lastScrollTop = 0;
        const header = document.getElementById('mainHeader');
        const scrollThreshold = 100; // Pixels to scroll before hiding

        window.addEventListener('scroll', function () {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > scrollThreshold) {
                if (scrollTop > lastScrollTop) {
                    // Scrolling down - hide header with slide up effect
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up - show header with slide down effect
                    header.style.transform = 'translateY(0)';
                }
            } else {
                // At top of page - always show header
                header.style.transform = 'translateY(0)';
            }

            lastScrollTop = scrollTop;
        });
    </script>
</body>

</html>