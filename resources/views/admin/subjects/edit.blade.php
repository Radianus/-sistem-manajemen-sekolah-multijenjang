<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Mata Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Informasi Mata Pelajaran:
                        {{ $subject->name }}</h3>

                    <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Mata Pelajaran')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $subject->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="code" :value="__('Kode Mata Pelajaran (Opsional)')" />
                            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code"
                                :value="old('code', $subject->code)" placeholder="Cth: MTK, BIN" />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $subject->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="level" :value="__('Jenjang Pendidikan (Opsional)')" />
                            <select id="level" name="level"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenjang</option>
                                <option value="SD" {{ old('level', $subject->level) == 'SD' ? 'selected' : '' }}>SD
                                </option>
                                <option value="SMP" {{ old('level', $subject->level) == 'SMP' ? 'selected' : '' }}>
                                    SMP</option>
                                <option value="SMA" {{ old('level', $subject->level) == 'SMA' ? 'selected' : '' }}>
                                    SMA</option>
                                <option value="SMK" {{ old('level', $subject->level) == 'SMK' ? 'selected' : '' }}>
                                    SMK</option>
                                <option value="Umum" {{ old('level', $subject->level) == 'Umum' ? 'selected' : '' }}>
                                    Umum (Semua Jenjang)</option>
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Mata Pelajaran') }}
                            </x-primary-button>
                            <a href="{{ route('admin.subjects.index') }}"
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
