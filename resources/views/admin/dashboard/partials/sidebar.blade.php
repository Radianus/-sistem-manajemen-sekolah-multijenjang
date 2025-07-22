 <aside id="sidebar"
     class="scrollbar-thin scrollbar-thumb-gray-900 scrollbar-track-gray-100 fixed inset-y-0 left-0 z-50 w-64 max-h-screen overflow-y-auto bg-gradient-to-b from-purple-50 to-white dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-200 shadow-lg
           transform transition-transform duration-300 ease-in-out
           md:translate-x-0 md:flex-shrink-0 md:block"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

     <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 h-16 flex items-center">
         <x-application-logo class="h-8 w-auto fill-current text-gray-800 dark:text-gray-200" />
         <h2 class="ml-4 text-lg font-semibold text-gray-900 dark:text-white">
             {{ $globalSettings->school_name ?? config('app.name', 'Akademika') }}
         </h2>
         <button @click="sidebarOpen = false"
             class="md:hidden ml-auto text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
             <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                 </path>
             </svg>
         </button>
     </div>
     <nav class="mt-2 space-y-2 px-4">
         <p class="text-sm font-medium text-gray-500 dark:text-gray-400 px-2">
             @if (Auth::user()->hasRole('admin_sekolah'))
                 Admin Sekolah
             @elseif(Auth::user()->hasRole('guru'))
                 Guru
             @elseif(Auth::user()->hasRole('siswa'))
                 Siswa
             @elseif(Auth::user()->hasRole('orang_tua'))
                 Orang Tua
             @else
                 Pengguna
             @endif
         </p>

         {{-- Dashboard Link (Selalu Ada) --}}
         <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7 7m-10-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h2m-7 0h2">
                 </path>
             </svg>
             {{ __('Dashboard') }}
         </x-nav-link>

         {{-- Menu untuk Admin Sekolah --}}
         @role('admin_sekolah')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Administrasi
             </div>
             <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M17 20h2a2 2 0 002-2V4a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h2m4-10h2m-2 6h2m-6 0h2m-2-6h2m8-6H9m-5 6h.01M7 10h.01">
                     </path>
                 </svg>
                 {{ __('Manajemen Pengguna') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                 </svg>
                 {{ __('Manajemen Kelas') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.students.index')" :active="request()->routeIs('admin.students.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M18 10a6 6 0 11-12 0 6 6 0 0112 0zm-6 9v2m0-3a8.962 8.962 0 005.042-1.921A10.024 10.024 0 0012 3a10.024 10.024 0 00-5.042 14.079A8.962 8.962 0 0012 18z">
                     </path>
                 </svg>
                 {{ __('Manajemen Siswa') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.subjects.index')" :active="request()->routeIs('admin.subjects.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                     </path>
                 </svg>
                 {{ __('Mata Pelajaran') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.teaching_assignments.index')" :active="request()->routeIs('admin.teaching_assignments.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0l-7 7m7-7v6"></path>
                 </svg>
                 {{ __('Penugasan Mengajar') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.grades.index')" :active="request()->routeIs('admin.grades.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                     </path>
                 </svg>
                 {{ __('Manajemen Nilai') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.attendances.index')" :active="request()->routeIs('admin.attendances.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
                 {{ __('Manajemen Absensi') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Manajemen Jadwal Pelajaran') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.edit')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M10.325 4.317c.502-1.288 1.488-2.274 2.776-2.776s2.274-.502 3.562-.008L21 6l-4 4-2-2-3-3-2-2zM4 16h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3a1 1 0 011-1zM4 4h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V5a1 1 0 011-1z">
                     </path>
                 </svg>
                 {{ __('Pengaturan Sistem') }}
             </x-nav-link>
         @endrole

         {{--  menu untuk GURU --}}
         @role('guru')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Akademik Guru
             </div>
             <x-nav-link :href="route('admin.grades.index')" :active="request()->routeIs('admin.grades.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                     </path>
                 </svg>
                 {{ __('Input Nilai') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.attendances.index')" :active="request()->routeIs('admin.attendances.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
                 {{ __('Absensi Siswa') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Jadwal Mengajar') }}
             </x-nav-link>
             <x-nav-link href="#">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 6.253v13.435m0-13.435a4.487 4.487 0 00-4.487 4.487v2.091M12 6.253a4.487 4.487 0 014.487 4.487v2.091m-7.436 0l.92-3.447A2.246 2.246 0 0116.518 10h1.968a2.246 2.246 0 012.235 2.09l-.754 2.822m-7.436 0l-.92 3.447A2.246 2.246 0 007.482 10H5.514a2.246 2.246 0 00-2.235 2.09l.754 2.822m13.774 0H7.236">
                     </path>
                 </svg>
                 {{ __('Materi & Tugas') }}
             </x-nav-link>
         @endrole
         {{-- Menu untuk Siswa --}}
         @role('siswa')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Akademik Siswa
             </div>
             {{-- Link ke Jadwal Pelajaran Siswa --}}
             <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Jadwal Pelajaran Saya') }}
             </x-nav-link>
             {{-- Link ke Nilai Siswa --}}
             <x-nav-link :href="route('admin.grades.index')" :active="request()->routeIs('admin.grades.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                     </path>
                 </svg>
                 {{ __('Nilai Saya') }}
             </x-nav-link>
             {{-- Link ke Absensi Siswa --}}
             <x-nav-link :href="route('admin.attendances.index')" :active="request()->routeIs('admin.attendances.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
                 {{ __('Absensi Saya') }}
             </x-nav-link>
             <x-nav-link href="#">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 6.253v13.435m0-13.435a4.487 4.487 0 00-4.487 4.487v2.091M12 6.253a4.487 4.487 0 014.487 4.487v2.091m-7.436 0l.92-3.447A2.246 2.246 0 0116.518 10h1.968a2.246 2.246 0 012.235 2.09l-.754 2.822m-7.436 0l-.92 3.447A2.246 2.246 0 007.482 10H5.514a2.246 2.246 0 00-2.235 2.09l.754 2.822m13.774 0H7.236">
                     </path>
                 </svg>
                 {{ __('Materi & Tugas') }}
             </x-nav-link>
         @endrole
         {{-- Menu untuk Orang Tua --}}
         @role('orang_tua')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Informasi Anak
             </div>
             <x-nav-link href="#">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                     </path>
                 </svg>
                 {{ __('Lihat Nilai Anak') }}
             </x-nav-link>
             <x-nav-link href="#">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
                 {{ __('Lihat Absensi Anak') }}
             </x-nav-link>
         @endrole
         {{-- Menu Komunikasi Umum (Untuk Semua Peran) --}}
         @role('admin_sekolah')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Komunikasi
             </div>
             <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M11 5.882V19.882c0 .591-.444 1.127-1.121 1.259C6.878 21.439 4 20.312 4 17.5V6H3a1 1 0 01-1-1V4c0-.552.448-1 1-1h18c.552 0 1 .448 1 1v1c0 .552-.448 1-1 1h-1v10.5c0 2.812-2.878 3.939-5.882 3.262C12.444 20.91 12 20.374 12 19.882V5.882z">
                     </path>
                 </svg>
                 {{ __('Pengumuman') }}
             </x-nav-link>
         @endrole

         @role('guru')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Komunikasi
             </div>
             <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M11 5.882V19.882c0 .591-.444 1.127-1.121 1.259C6.878 21.439 4 20.312 4 17.5V6H3a1 1 0 01-1-1V4c0-.552.448-1 1-1h18c.552 0 1 .448 1 1v1c0 .552-.448 1-1 1h-1v10.5c0 2.812-2.878 3.939-5.882 3.262C12.444 20.91 12 20.374 12 19.882V5.882z">
                     </path>
                 </svg>
                 {{ __('Pengumuman') }}
             </x-nav-link>
         @endrole

         @role('siswa')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Komunikasi
             </div>
             <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M11 5.882V19.882c0 .591-.444 1.127-1.121 1.259C6.878 21.439 4 20.312 4 17.5V6H3a1 1 0 01-1-1V4c0-.552.448-1 1-1h18c.552 0 1 .448 1 1v1c0 .552-.448 1-1 1h-1v10.5c0 2.812-2.878 3.939-5.882 3.262C12.444 20.91 12 20.374 12 19.882V5.882z">
                     </path>
                 </svg>
                 {{ __('Pengumuman') }}
             </x-nav-link>
         @endrole

         @role('orang_tua')
             <div class="block px-2 py-2 text-xs text-gray-400 uppercase tracking-wider mt-4">
                 Komunikasi
             </div>
             <x-nav-link :href="route('admin.announcements.index')" :active="request()->routeIs('admin.announcements.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M11 5.882V19.882c0 .591-.444 1.127-1.121 1.259C6.878 21.439 4 20.312 4 17.5V6H3a1 1 0 01-1-1V4c0-.552.448-1 1-1h18c.552 0 1 .448 1 1v1c0 .552-.448 1-1 1h-1v10.5c0 2.812-2.878 3.939-5.882 3.262C12.444 20.91 12 20.374 12 19.882V5.882z">
                     </path>
                 </svg>
                 {{ __('Pengumuman') }}
             </x-nav-link>
         @endrole

         @role('admin_sekolah')
             <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.155C2.604 14.614 2 12.38 2 10c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                     </path>
                 </svg>
                 {{ __('Pesan Internal') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.calendar_events.index')" :active="request()->routeIs('admin.calendar_events.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Kalender Akademik') }}
             </x-nav-link>
         @endrole
         @role('guru')
             <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.155C2.604 14.614 2 12.38 2 10c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                     </path>
                 </svg>
                 {{ __('Pesan Internal') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.calendar_events.index')" :active="request()->routeIs('admin.calendar_events.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Kalender Akademik') }}
             </x-nav-link>
         @endrole

         @role('siswa')
             <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.155C2.604 14.614 2 12.38 2 10c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                     </path>
                 </svg>
                 {{ __('Pesan Internal') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.calendar_events.index')" :active="request()->routeIs('admin.calendar_events.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Kalender Akademik') }}
             </x-nav-link>
         @endrole

         @role('orang_tua')
             <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.155C2.604 14.614 2 12.38 2 10c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                     </path>
                 </svg>
                 {{ __('Pesan Internal') }}
             </x-nav-link>
             <x-nav-link :href="route('admin.calendar_events.index')" :active="request()->routeIs('admin.calendar_events.*')">
                 <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M8 7V3m8 4V3m-9 8h.01M7 11h.01M7 15h.01M16 15h.01M17 12H7m14-4a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V8z">
                     </path>
                 </svg>
                 {{ __('Kalender Akademik') }}
             </x-nav-link>
         @endrole



     </nav>
 </aside>
