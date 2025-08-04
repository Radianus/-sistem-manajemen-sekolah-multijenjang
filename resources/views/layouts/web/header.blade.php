<header x-data="{ scrolled: false, currentTheme: localStorage.getItem('theme') || 'light' }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 50)"
    :class="scrolled
        ?
        'bg-white dark:bg-gray-800 shadow-sm' :
        'bg-transparent dark:bg-gray-900'"
    class="sticky top-0 z-50 transition-colors duration-300 bg-white/50 dark:bg-gray-900/50 backdrop-blur-md shadow-md text-gray-900 dark:text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Logo Sekolah --}}
        <a href="{{ route('web.home') }}" class="text-xl font-bold dark:text-white">
            {{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}
        </a>

        {{-- Menu Desktop --}}
        <nav class="hidden md:flex items-center space-x-6">
            @foreach ([['label' => 'Beranda', 'route' => 'web.home'], ['label' => 'Berita', 'route' => 'web.news.index'], ['label' => 'Galeri', 'route' => 'web.gallery.index'], ['label' => 'Kalender', 'route' => 'web.calendar.index'], ['label' => 'Kontak', 'route' => 'web.contact'], ['label' => 'Tentang Kami', 'route' => 'web.about']] as $item)
                @php
                    $isActive = request()->routeIs($item['route'])
                        ? 'text-blue-600 dark:text-blue-400 font-semibold'
                        : 'text-gray-600 dark:text-gray-300';
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="px-3 py-2 transition-colors hover:text-blue-600 dark:hover:text-blue-400 {{ $isActive }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            {{-- Cek apakah user sudah login --}}
            @if (Auth::check())
                <div class="relative group">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150 transition-colors">
                        @if (Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
                                class="h-8 w-8 rounded-full object-cover mr-2">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                                alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover mr-2">
                        @endif
                    </button>
                    {{-- Dropdown muncul saat hover --}}
                    <div
                        class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg opacity-0 group-hover:opacity-100 group-hover:translate-y-1 transition-all z-50">
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Jika belum login, tampilkan link login --}}
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-blue-700 transition">
                    Login
                </a>
            @endif

            {{-- Theme Toggle --}}
            <button @click="toggleTheme()" aria-label="Toggle dark mode"
                class="p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <template x-if="currentTheme === 'light'">
                    <svg class="h-6 w-6 fill-yellow-500" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 3.325l-.707.707M5.388 5.388l-.707-.707M18.325 8.675l.707-.707M5.388 18.325l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                    </svg>
                </template>
                <template x-if="currentTheme === 'dark'">
                    <svg class="h-6 w-6 fill-violet-700" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </template>
            </button>
        </nav>

        {{-- Hamburger Mobile --}}
        <button @click="open = !open"
            class="md:hidden p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
            <svg class="h-6 w-6" x-show="!open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg class="h-6 w-6" x-show="open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-collapse
        class="md:hidden border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        <nav class="flex flex-col space-y-2 p-4">
            @foreach ([['label' => 'Beranda', 'route' => 'web.home'], ['label' => 'Berita', 'route' => 'web.news.index'], ['label' => 'Galeri', 'route' => 'web.gallery.index'], ['label' => 'Kalender', 'route' => 'web.calendar.index'], ['label' => 'Kontak', 'route' => 'web.contact'], ['label' => 'Tentang Kami', 'route' => 'web.about']] as $item)
                <a href="{{ route($item['route']) }}"
                    class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    {{ $item['label'] }}
                </a>
            @endforeach

            {{-- Mobile Menu - Auth Check --}}
            @if (Auth::check())
                <div x-data="{ open: false }" class="relative px-3 py-2 text-gray-700 dark:text-gray-300">
                    <button @click="open = !open" class="w-full flex justify-between items-center focus:outline-none">
                        <span>Halo, {{ Auth::user()->name }}</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="mt-2 bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden z-50">
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 dark:text-red-400">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                    Login
                </a>
            @endif


            {{-- Theme Toggle Mobile --}}
            <button @click="toggleTheme()"
                class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 3.325l-.707.707M5.388 5.388l-.707-.707M18.325 8.675l.707-.707M5.388 18.325l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
                </svg>
                <span x-text="currentTheme === 'dark' ? 'Tema Terang' : 'Tema Gelap'"></span>
            </button>
        </nav>
    </div>
</header>
