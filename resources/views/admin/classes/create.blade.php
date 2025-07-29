<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kelas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Informasi Kelas</h3>

                    <form method="POST" action="{{ route('admin.classes.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Nama Kelas')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="level" :value="__('Jenjang Pendidikan')" /> {{-- UBAH LABEL --}}
                            <select id="level" name="level" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenjang</option>
                                @foreach ($levelOptions as $level)
                                    {{-- UBAH VARIABEL --}}
                                    <option value="{{ $level }}" {{ old('level') == $level ? 'selected' : '' }}>
                                        {{ $level }}</option> {{-- UBAH VALUE --}}
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('level')" class="mt-2" /> {{-- UBAH ERROR --}}
                        </div>


                        <div class="mb-4">
                            <x-input-label for="grade_level" :value="__('Tingkat Kelas')" />
                            <x-text-input id="grade_level" class="block mt-1 w-full" type="text" name="grade_level"
                                :value="old('grade_level')" placeholder="Cth: 1, 7, 10" />
                            <x-input-error :messages="$errors->get('grade_level')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="academic_year" :value="__('Tahun Ajaran')" />
                            <x-text-input id="academic_year" class="block mt-1 w-full" type="text"
                                name="academic_year" :value="old('academic_year', date('Y') . '/' . (date('Y') + 1))" placeholder="Cth: 2024/2025" />
                            <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="homeroom_teacher_id" :value="__('Wali Kelas')" />
                            <select id="homeroom_teacher_id" name="homeroom_teacher_id"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Wali Kelas (Opsional)</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('homeroom_teacher_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Kelas') }}
                            </x-primary-button>
                            <a href="{{ route('admin.classes.index') }}"
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
