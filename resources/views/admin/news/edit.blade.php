<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Berita: {{ $news->title }}
                    </h3>

                    <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Berita')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $news->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Isi Berita')" />
                            <textarea id="content" name="content"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="10" required>{{ old('content', $news->content) }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Gambar Utama (Opsional)')" />
                            @if ($news->image_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($news->image_path) }}" alt="Gambar Berita Saat Ini"
                                        class="w-64 h-auto rounded-lg">
                                </div>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" name="remove_image" id="remove_image" value="1"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-red-600 shadow-sm focus:ring-red-500">
                                    <label for="remove_image"
                                        class="ml-2 text-sm text-gray-600 dark:text-gray-400">Hapus gambar saat
                                        ini</label>
                                </div>
                            @endif
                            <input id="image" name="image" type="file"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300 dark:hover:file:bg-blue-800" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ukuran maksimal 2MB. Format: JPG,
                                PNG, GIF.</p>
                        </div>

                        <div class="mb-6">
                            <x-input-label for="published_at" :value="__('Tanggal Publikasi (Opsional)')" />
                            <x-text-input id="published_at" class="block mt-1 w-full" type="datetime-local"
                                name="published_at" :value="old(
                                    'published_at',
                                    $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '',
                                )" />
                            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Jika dikosongkan, berita akan
                                disimpan sebagai draf.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Berita') }}
                            </x-primary-button>
                            <a href="{{ route('admin.news.index') }}"
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
