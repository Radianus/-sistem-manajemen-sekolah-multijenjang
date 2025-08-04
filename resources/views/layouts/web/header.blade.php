  <header id="#" class="bg-white dark:bg-gray-800 shadow-sm transition-colors sticky top-0 z-50 ">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
          {{-- Logo dan Nama Sekolah --}}
          <div class="flex items-center">
              <a href="{{ route('web.home') }}"
                  class="text-xl font-bold dark:text-white">{{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}</a>
          </div>
          {{-- Desktop Menu --}}
          <nav class="hidden md:flex space-x-6 items-center">
              <a href="{{ route('web.home') }}"
                  class="text-gray-600 dark:text-gray-300 px-3 py-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a>
              <a href="{{ route('web.news.index') }}"
                  class="text-gray-600 dark:text-gray-300 px-3 py-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Berita</a>
              <a href="{{ route('login') }}"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">Login</a>
              {{-- Toggle Dark/Light Mode --}}
              <button @click="toggleTheme()" aria-label="Toggle dark mode"
                  class="p-2 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                  <svg class="h-6 w-6 fill-violet-700"
                      :class="{ 'hidden': currentTheme === 'dark', 'block': currentTheme === 'light' }"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                      </path>
                  </svg>
                  <svg class="h-6 w-6 fill-yellow-500"
                      :class="{ 'hidden': currentTheme === 'light', 'block': currentTheme === 'dark' }"
                      fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 3.325l-.707.707M5.388 5.388l-.707-.707M18.325 8.675l.707-.707M5.388 18.325l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z">
                      </path>
                  </svg>
              </button>
          </nav>

          {{-- Mobile Menu --}}
          <div class="flex items-center md:hidden">
              <button @click="open = !open" type="button"
                  class="inline-flex items-center p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition-colors">
                  <svg class="h-6 w-6" :class="{ 'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                  <svg class="h-6 w-6" :class="{ 'hidden': !open, 'block': open }" fill="none" viewBox="0 0 24 24"
                      stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
              </button>
          </div>
      </div>

      {{-- Responsive Mobile Menu --}}
      <div x-show="open" x-collapse
          class="md:hidden bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
          <nav class="flex flex-col p-4 space-y-2">
              <a href="{{ route('web.home') }}"
                  class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">Beranda</a>
              <a href="{{ route('web.news.index') }}"
                  class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">Berita</a>
              <a href="{{ route('login') }}"
                  class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">Login</a>

              {{-- Toggle Dark/Light Mode Mobile --}}
              <button @click="toggleTheme()"
                  class="block w-full text-left px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors">
                  <div class="flex items-center">
                      <svg class="h-5 w-5 mr-2" :class="{ 'block': currentTheme === 'dark' }" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 3.325l-.707.707M5.388 5.388l-.707-.707M18.325 8.675l.707-.707M5.388 18.325l-.707.707M12 7a5 5 0 100 10 5 5 0 000-10z">
                          </path>
                      </svg>
                      <span x-text="currentTheme === 'dark' ? 'Tema Terang' : 'Tema Gelap'"></span>
                  </div>
              </button>
          </nav>
      </div>
  </header>
