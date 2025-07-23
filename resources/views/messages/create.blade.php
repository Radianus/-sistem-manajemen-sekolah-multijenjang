<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kirim Pesan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Tulis Pesan</h3>

                    <form method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data">
                        {{-- TAMBAH INI --}}
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="receiver_id" :value="__('Penerima')" />
                            <select id="receiver_id" name="receiver_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Penerima</option>
                                @foreach ($users as $user)
                                    @if ($user->id !== Auth::id())
                                        <option value="{{ $user->id }}"
                                            {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                            ({{ ucwords(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'Pengguna')) }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('receiver_id')" class="mt-2" />
                            @if ($users->where('id', '!=', Auth::id())->isEmpty())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada pengguna lain untuk
                                    dikirimi pesan.</p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <x-input-label for="subject" :value="__('Subjek (Opsional)')" />
                            <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject"
                                :value="old('subject')" />
                            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Isi Pesan')" />
                            <textarea id="content" name="content"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="attachments" :value="__('Lampirkan File (Opsional)')" />
                            <input type="file" name="attachments[]" id="attachments" multiple
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300 dark:hover:file:bg-blue-800" />
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                            <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" /> {{-- Untuk error tiap file --}}
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ukuran maksimal 10MB per file.
                                Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG, ZIP, RAR.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Kirim Pesan') }}
                            </x-primary-button>
                            <a href="{{ route('messages.index') }}"
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
