@props(['mobile' => false])

<button @click="toggleTheme()" aria-label="Toggle dark mode"
    class="flex items-center {{ $mobile ? 'w-full text-left px-3 py-2' : 'p-2' }} rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
    <svg x-show="currentTheme === 'light'" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg x-show="currentTheme === 'dark'" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 3.325l-.707.707M5.388 5.388l-.707-.707M18.325 8.675l.707-.707M5.388 18.325l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z" />
    </svg>
    @if ($mobile)
        <span class="ml-2" x-text="currentTheme === 'dark' ? 'Tema Terang' : 'Tema Gelap'"></span>
    @endif
</button>
