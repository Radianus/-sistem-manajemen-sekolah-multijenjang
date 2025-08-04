<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon-32x32.png') }}" type="image/png" class="dark:bg-gray-800 bg-red-800">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="font-sans text-gray-900 bg-white antialiased dark:bg-gray-900 dark:text-gray-100 transition-colors">
    <div x-data="{
        open: false,
        currentTheme: localStorage.theme || 'light',
        toggleTheme() {
            this.currentTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', this.currentTheme);
            document.documentElement.classList.toggle('dark', this.currentTheme === 'dark');
        }
    }" x-init="document.documentElement.classList.toggle('dark', currentTheme === 'dark');" class="min-h-screen flex flex-col">
        @include('layouts.web.header')
        <main class="flex-grow">
            @yield('content')
        </main>
        @include('layouts.web.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            document.getElementById('content-wrapper')?.classList.add('opacity-100');
            const navbar = document.getElementById('main-navbar');
            const isHome = window.location.pathname === '/' || window.location.pathname === '/index';
            if (isHome) {
                // Di halaman home, header disembunyikan sampai scroll
                navbar.classList.add('hidden');
                navbar.classList.remove('opacity-100');

                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.remove('hidden');
                        navbar.classList.add('opacity-100');
                    } else {
                        navbar.classList.add('hidden');
                        navbar.classList.remove('opacity-100');
                    }
                });
            } else {
                // Di halaman lain, header langsung muncul
                navbar.classList.remove('hidden');
                navbar.classList.add('opacity-100');
            }
        });
    </script>

    <script>
        window.addEventListener("scroll", function() {
            const header = document.getElementById("header");
            const links = header.querySelectorAll(".header-link");

            if (window.scrollY > 50) {
                header.classList.add("bg-white", "shadow-md");
                links.forEach(link => link.classList.add("text-black"));
                links.forEach(link => link.classList.remove("text-white"));
            } else {
                header.classList.remove("bg-white", "shadow-md");
                links.forEach(link => link.classList.remove("text-black"));
                links.forEach(link => link.classList.add("text-white"));
            }
        });
    </script>
</body>

</html>
