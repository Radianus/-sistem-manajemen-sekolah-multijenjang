<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Pengumuman Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Informasi Pengumuman</h3>

                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Pengumuman')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Isi Pengumuman')" />
                            <textarea id="content" name="content"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="published_at" :value="__('Tanggal Publikasi (Opsional)')" />
                            <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local"
                                name="published_at" :value="old('published_at')" />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="expires_at" :value="__('Tanggal Kadaluarsa (Opsional)')" />
                            <x-text-input id="expires_at" class="block mt-1 w-full" type="datetime-local"
                                name="expires_at" :value="old('expires_at')" />
                            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="target_roles" :value="__('Ditargetkan Untuk Peran')" />
                            <select id="target_roles" name="target_roles[]" multiple
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                size="5">
                                @foreach ($roles as $roleName)
                                    <option value="{{ $roleName }}"
                                        {{ in_array($roleName, old('target_roles', [])) ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $roleName)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('target_roles')" class="mt-2" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pilih "All" untuk semua pengguna,
                                atau pilih beberapa peran (tekan Ctrl/Command untuk multi-pilih).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Pengumuman') }}
                            </x-primary-button>
                            <a href="{{ route('admin.announcements.index') }}"
                                class="ml-4 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
