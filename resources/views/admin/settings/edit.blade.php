<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Informasi Dasar Sekolah</h3>

                    <form method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT') {{-- Selalu gunakan PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="school_name" :value="__('Nama Sekolah')" />
                            <x-text-input id="school_name" class="block mt-1 w-full" type="text" name="school_name"
                                :value="old('school_name', $setting->school_name)" required autofocus />
                            <x-input-error :messages="$errors->get('school_name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="school_address" :value="__('Alamat Sekolah')" />
                            <textarea id="school_address" name="school_address"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="3">{{ old('school_address', $setting->school_address) }}</textarea>
                            <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="school_phone" :value="__('Nomor Telepon Sekolah')" />
                            <x-text-input id="school_phone" class="block mt-1 w-full" type="text" name="school_phone"
                                :value="old('school_phone', $setting->school_phone)" />
                            <x-input-error :messages="$errors->get('school_phone')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="school_email" :value="__('Email Sekolah')" />
                            <x-text-input id="school_email" class="block mt-1 w-full" type="email" name="school_email"
                                :value="old('school_email', $setting->school_email)" />
                            <x-input-error :messages="$errors->get('school_email')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="current_academic_year" :value="__('Tahun Ajaran Saat Ini')" />
                            <x-text-input id="current_academic_year" class="block mt-1 w-full" type="text"
                                name="current_academic_year" :value="old(
                                    'current_academic_year',
                                    $setting->current_academic_year ?? date('Y') . '/' . (date('Y') + 1),
                                )" placeholder="Cth: 2025/2026" />
                            <x-input-error :messages="$errors->get('current_academic_year')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
