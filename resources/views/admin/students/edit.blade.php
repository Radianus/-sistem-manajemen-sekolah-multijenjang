<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Informasi Siswa:
                        {{ $student->user->name ?? 'N/A' }}</h3>

                    <form method="POST" action="{{ route('admin.students.update', $student) }}">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="user_id" :value="__('Akun Pengguna Siswa')" />
                            <select id="user_id" name="user_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $student->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" />
                            <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis"
                                :value="old('nis', $student->nis)" required />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nisn" :value="__('Nomor Induk Siswa Nasional (NISN)')" />
                            <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn"
                                :value="old('nisn', $student->nisn)" />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                            <select id="gender" name="gender" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki"
                                    {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="Perempuan"
                                    {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date"
                                name="date_of_birth" :value="old('date_of_birth', $student->date_of_birth)" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $student->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                                :value="old('phone_number', $student->phone_number)" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="school_class_id" :value="__('Kelas')" />
                            <select id="school_class_id" name="school_class_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('school_class_id', $student->school_class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_class_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Perbarui Siswa') }}
                            </x-primary-button>
                            <a href="{{ route('admin.students.index') }}"
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
