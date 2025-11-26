{{--
Vista de artículo individual del diario digital

Esta vista muestra un artículo específico con todo su contenido.
Incluye:
- Header con navegación
- Contenido completo del artículo
- Información del autor
- Comentarios (si están habilitados)
- Artículos relacionados
- Compartir en redes sociales

Datos recibidos del ArticleController:
- $article: Artículo completo con relaciones (autor, sección, comentarios)
--}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->seo_title ?: $article->title }} - CMS Digital</title>
    <meta name="description" content="{{ $article->meta_description ?: $article->excerpt }}">

    @if($article->meta_keywords)
        <meta name="keywords"
            content="{{ is_array($article->meta_keywords) ? implode(', ', $article->meta_keywords) : $article->meta_keywords }}">
    @endif

    <meta name="author" content="{{ $article->visible_author_name }}">
    <meta property="article:published_time" content="{{ $article->published_at->toISOString() }}">
    <meta property="article:section" content="{{ $article->section->name }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($article->getFirstMediaUrl('cover'))
        <meta property="og:image" content="{{ $article->getFirstMediaUrl('cover') }}">
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
        .article-content {
            line-height: 1.8;
        }

        .article-content p {
            margin-bottom: 1.25rem;
        }

        .article-content h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            color: #1f2937;
        }

        .article-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.5rem 0 0.75rem 0;
            color: #374151;
        }

        .article-content ul,
        .article-content ol {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .article-content li {
            margin-bottom: 0.5rem;
        }

        .article-content blockquote {
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #4b5563;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header with auto-hide on scroll -->
    <header id="mainHeader"
        class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50 transition-transform duration-300 ease-in-out">
        <div class="container mx-auto px-4">
            <div class="py-4">
                <div class="flex items-center justify-between">
                    <!-- Navigation Link -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-home mr-1"></i>
                            Inicio
                        </a>
                        <span class="text-gray-400">•</span>
                        <a href="{{ route('sections.show', $article->section->slug) }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors">
                            {{ $article->section->name }}
                        </a>
                    </div>

                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo CMS Digital"
                                class="h-24 md:h-28 lg:h-32 w-auto">
                        </a>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center space-x-2">
                        <button onclick="shareOnFacebook()"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button onclick="shareOnTwitter()"
                            class="p-2 text-sky-500 hover:bg-sky-50 rounded-full transition-colors">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button onclick="copyToClipboard()"
                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Spacer to prevent content jump -->
    <div class="h-28 md:h-32 lg:h-36"></div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Article Header -->
            <article class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Featured Image -->
                @if($article->getFirstMediaUrl('cover'))
                    <div class="w-full h-64 md:h-96">
                        <img src="{{ $article->getFirstMediaUrl('cover') }}" alt="{{ $article->title }}"
                            class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Article Content -->
                <div class="p-6 md:p-8">
                    <!-- Volanta and Date -->
                    <div class="flex items-center mb-4 text-sm">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full">
                            {{ $article->volanta ?? $article->section->name }}
                        </span>
                        <span class="ml-4 text-gray-500">
                            {{ $article->published_at->format('d \d\e F \d\e Y \a \l\a\s H:i') }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $article->title }}
                    </h1>

                    <!-- Excerpt -->
                    <div class="text-lg text-gray-600 mb-6 font-medium leading-relaxed">
                        {{ $article->excerpt }}
                    </div>

                    <!-- Author and Reading Time -->
                    <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-8">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode($article->visible_author_name) }}&background=4f46e5&color=fff"
                                alt="{{ $article->visible_author_name }}">
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $article->visible_author_name }}</p>
                                <p class="text-sm text-gray-500">Autor</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Tiempo de lectura</p>
                            <p class="text-sm font-medium text-gray-900">{{ $article->reading_time }} minutos</p>
                        </div>
                    </div>

                    <!-- Article Body -->
                    <div class="article-content text-gray-700 text-lg">
                        {!! nl2br(e($article->body)) !!}
                    </div>

                    <!-- Gallery Images Section -->
                    @if($article->getMedia('gallery')->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Galería de imágenes</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($article->getMedia('gallery') as $image)
                                    <div class="bg-gray-100 rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition-shadow"
                                        onclick="openImageModal('{{ $image->getUrl() }}', '{{ $image->getCustomProperty('alt_text', $article->title) }}')">
                                        <img src="{{ $image->getUrl('thumb') }}"
                                            alt="{{ $image->getCustomProperty('alt_text', $article->title) }}"
                                            class="w-full h-48 object-cover hover:scale-105 transition-transform">
                                        @if($image->getCustomProperty('alt_text'))
                                            <div class="p-3 bg-white">
                                                <p class="text-sm text-gray-600">{{ $image->getCustomProperty('alt_text') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Videos Section -->
                    @if($article->getMedia('videos')->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Videos del artículo</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($article->getMedia('videos') as $video)
                                    <div class="bg-gray-100 rounded-lg overflow-hidden">
                                        <video controls class="w-full h-64 object-cover">
                                            <source src="{{ $video->getUrl() }}" type="{{ $video->mime_type }}">
                                            <p class="p-4 text-gray-600">
                                                Tu navegador no soporta la reproducción de video.
                                                <a href="{{ $video->getUrl() }}" class="text-blue-600 underline">Descargar
                                                    video</a>
                                            </p>
                                        </video>
                                        @if($video->getCustomProperty('alt_text'))
                                            <div class="p-3 bg-white">
                                                <p class="text-sm text-gray-600">{{ $video->getCustomProperty('alt_text') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($article->tags && count($article->tags) > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">Etiquetas:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <span
                                        class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-200 transition-colors">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Share Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Compartir artículo:</h3>
                        <div class="flex space-x-3">
                            <button onclick="shareOnFacebook()"
                                class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fab fa-facebook-f mr-2"></i>
                                Facebook
                            </button>
                            <button onclick="shareOnTwitter()"
                                class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-md hover:bg-sky-600 transition-colors">
                                <i class="fab fa-twitter mr-2"></i>
                                Twitter
                            </button>
                            <button onclick="copyToClipboard()"
                                class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                <i class="fas fa-link mr-2"></i>
                                Copiar enlace
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @php
                $relatedArticles = \App\Models\Article::where('section_id', $article->section_id)
                    ->where('id', '!=', $article->id)
                    ->where('status', 'published')
                    ->orderBy('published_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            @if($relatedArticles->count() > 0)
                <section class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Artículos relacionados</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedArticles as $relatedArticle)
                            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                @if($relatedArticle->getFirstMediaUrl('cover'))
                                    <img src="{{ $relatedArticle->getFirstMediaUrl('cover', 'medium') }}"
                                        alt="{{ $relatedArticle->title }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-bold text-lg mb-2 leading-tight">
                                        <a href="{{ route('articles.show', $relatedArticle->slug) }}"
                                            class="text-gray-900 hover:text-blue-600 transition-colors">
                                            {{ Str::limit($relatedArticle->title, 60) }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-3">
                                        {{ Str::limit($relatedArticle->excerpt, 100) }}
                                    </p>
                                    <div class="text-xs text-gray-500">
                                        {{ $relatedArticle->published_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
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
                        Tu fuente confiable de noticias y actualidad.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="hover:opacity-80 transition-opacity">
                            <img src="{{ asset('images/x-logo.jpg') }}" alt="X (Twitter)"
                                class="w-5 h-5 object-contain filter brightness-0 invert opacity-60 hover:opacity-100 transition-opacity">
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="https://wa.me/34900123456" target="_blank"
                            class="text-gray-400 hover:text-green-400 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Inicio</a></li>
                        <li><a href="{{ route('sections.show', $article->section->slug) }}"
                                class="text-gray-300 hover:text-white">{{ $article->section->name }}</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-blue-400 hover:text-blue-300">Panel Admin</a>
                            </li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Contacto</h4>
                    <div class="text-sm text-gray-300 space-y-2">
                        <p><i class="fas fa-envelope mr-2"></i> contacto@cmsdigital.com</p>
                        <p><i class="fas fa-phone mr-2"></i> +34 900 123 456</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} CMS Digital. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4"
        onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full relative">
            <button onclick="closeImageModal()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 text-2xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
            <div id="modalCaption"
                class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4 text-center"></div>
        </div>
    </div>

    
    <script>
        // Auto-hide header on scroll
        let lastScrollTop = 0;
        const header = document.getElementById('mainHeader');
        const scrollThreshold = 100; // Pixels to scroll before hiding
        
        window.addEventListener('scroll', function() {
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
        
        function shareOnFacebook() {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('{{ $article->title }}');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
        }
        
        function shareOnTwitter() {
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent('{{ $article->title }}');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
        }
        
        function copyToClipboard() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Enlace copiado al portapapeles');
            }, function(err) {
                console.error('Error al copiar: ', err);
            });
        }
        
        function openImageModal(imageUrl, caption) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');
            
            modalImage.src = imageUrl;
            modalImage.alt = caption;
            modalCaption.textContent = caption;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
</body>

</html>