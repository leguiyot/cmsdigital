<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'CMS Digital'))</title>

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @hasSection('navigation')
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>
        </div>
        
        @stack('scripts')
    </body>
</html>
