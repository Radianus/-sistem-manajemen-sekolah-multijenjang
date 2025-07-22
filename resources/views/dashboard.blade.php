<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            @if (Auth::user()->hasRole('admin_sekolah'))
                - Admin Sekolah
            @elseif(Auth::user()->hasRole('guru'))
                - Guru
            @elseif(Auth::user()->hasRole('siswa'))
                - Siswa
            @elseif(Auth::user()->hasRole('orang_tua'))
                - Orang Tua
            @endif
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Konten Dashboard Spesifik untuk Admin --}}
                    @role('admin_sekolah')
                        @include('admin.dashboard.partials.admin')
                    @endrole

                    {{-- Konten admin.Dashboard Spesifik untuk Guru --}}
                    @role('guru')
                        @include('admin.dashboard.partials.guru')
                    @endrole

                    {{-- Konten admin.Dashboard Spesifik untuk Siswa --}}
                    @role('siswa')
                        @include('admin.dashboard.partials.siswa')
                    @endrole
                    {{-- Konten admin.Dashboard Spesifik untuk Orang Tua (placeholder saja) --}}
                    @role('orang_tua')
                        @include('admin.dashboard.partials.orang_tua')
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
