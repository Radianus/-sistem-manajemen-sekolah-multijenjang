<header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-40 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        {{-- Logo --}}
        <a href="{{ route('web.home') }}" class="text-xl font-bold dark:text-white">
            {{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}
        </a>
        {{-- Desktop Menu --}}
        <nav class="hidden md:flex space-x-6 items-center">
            <x-nav-link href="{{ route('web.home') }}" label="Beranda" />
            <x-nav-link href="{{ route('web.news.index') }}" label="Berita" />
            {{-- <x-button-link href="{{ route('login') }}" label="Login" /> --}}
            {{-- Toggle Theme --}}
            <x-theme-toggle />
        </nav>

        {{-- Mobile Toggle --}}
        <button @click="open = !open"
            class="md:hidden text-gray-600 dark:text-gray-300 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
            <template x-if="!open">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </template>
            <template x-if="open">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M6 18L18 6M6 6l12 12" />
                </svg>
            </template>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-collapse
        class="md:hidden bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <nav class="flex flex-col p-4 space-y-2">
            <x-nav-link href="{{ route('web.home') }}" label="Beranda" />
            <x-nav-link href="{{ route('web.news.index') }}" label="Berita" />
            <x-nav-link href="{{ route('login') }}" label="Login" />
            <x-theme-toggle mobile />
        </nav>
    </div>
</header>
