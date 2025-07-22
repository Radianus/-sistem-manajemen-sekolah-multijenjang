<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Siswa Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Informasi Siswa</h3>

                    <form method="POST" action="{{ route('admin.students.store') }}" x-data="{
                        userCreationMode: '{{ old('user_creation_mode', 'new') }}', // 'new' atau 'existing'
                        existingUsers: @json($existingUsers),
                        selectedExistingUserId: '{{ old('user_id', '') }}', // Use user_id as old input key
                    
                        // New user fields state (still needed to display old input if validation fails)
                        newUserName: '{{ old('new_user_name', '') }}',
                        newUserEmail: '{{ old('new_user_email', '') }}',
                        newUserPassword: '{{ old('new_user_password', '') }}', // Retain old value
                        newUserPasswordConfirmation: '{{ old('new_user_password_confirmation', '') }}', // Retain old value
                    }">
                        @csrf

                        <div class="mb-6">
                            <x-input-label :value="__('Mode Pembuatan Akun Pengguna')" />
                            <div class="mt-2 flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" x-model="userCreationMode" value="new"
                                        name="user_creation_mode"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Buat Akun Pengguna
                                        Baru</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" x-model="userCreationMode" value="existing"
                                        name="user_creation_mode"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Pilih Akun Pengguna Yang
                                        Sudah Ada</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="userCreationMode === 'new'"
                            class="p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-6">
                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Detail Akun Baru
                            </h4>
                            <div class="mb-4">
                                <x-input-label for="new_user_name" :value="__('Nama Lengkap Akun')" />
                                <x-text-input id="new_user_name" class="block mt-1 w-full" type="text"
                                    name="new_user_name" x-model="newUserName" />
                                <x-input-error :messages="$errors->get('new_user_name')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="new_user_email" :value="__('Email Akun')" />
                                <x-text-input id="new_user_email" class="block mt-1 w-full" type="email"
                                    name="new_user_email" x-model="newUserEmail" />
                                <x-input-error :messages="$errors->get('new_user_email')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="new_user_password" :value="__('Password Akun')" />
                                <x-text-input id="new_user_password" class="block mt-1 w-full" type="password"
                                    name="new_user_password" x-model="newUserPassword" />
                                <x-input-error :messages="$errors->get('new_user_password')" class="mt-2" />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="new_user_password_confirmation" :value="__('Konfirmasi Password Akun')" />
                                <x-text-input id="new_user_password_confirmation" class="block mt-1 w-full"
                                    type="password" name="new_user_password_confirmation"
                                    x-model="newUserPasswordConfirmation" />
                                <x-input-error :messages="$errors->get('new_user_password_confirmation')" class="mt-2" />
                            </div>
                            {{-- HAPUS TOMBOL "Buat Akun Pengguna" DI SINI --}}
                            {{-- <div class="flex justify-end">
                                <button type="button" @click="createUserAccount()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Buat Akun Pengguna
                                </button>
                            </div> --}}
                        </div>

                        <div x-show="userCreationMode === 'existing'"
                            class="p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-6">
                            <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Pilih Akun Existing
                            </h4>
                            <div class="mb-4">
                                <x-input-label for="existing_user_id" :value="__('Akun Pengguna Siswa')" />
                                <select id="existing_user_id" name="user_id" x-model="selectedExistingUserId"
                                    {{-- Name changed to user_id for backend --}}
                                    class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Pilih Akun Pengguna</option>
                                    <template x-for="user in existingUsers" :key="user.id">
                                        <option :value="user.id" x-text="`${user.name} (${user.email})`"
                                            :selected="user.id == selectedExistingUserId"></option>
                                    </template>
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" /> {{-- Error message for user_id --}}
                                <p x-show="existingUsers.length === 0"
                                    class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada akun pengguna dengan
                                    peran 'siswa' yang belum terdaftar dan belum memiliki profil siswa.</p>
                            </div>
                        </div>

                        {{-- HAPUS FIELD TERSEMBUNYI user_id createdUserId, selectedExistingUserId AKAN DIKIRIM OLEH FIELD YANG TAMPAK --}}
                        {{-- <input type="hidden" name="user_id" x-model="createdUserId"> --}}


                        <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Detail Siswa</h4>

                        <div class="mb-4">
                            <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" />
                            <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis"
                                :value="old('nis')" required />
                            <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nisn" :value="__('Nomor Induk Siswa Nasional (NISN)')" />
                            <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn"
                                :value="old('nisn')" />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="gender" :value="__('Jenis Kelamin')" />
                            <select id="gender" name="gender" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="date_of_birth" :value="__('Tanggal Lahir')" />
                            <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date"
                                name="date_of_birth" :value="old('date_of_birth')" />
                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" name="address"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="phone_number" :value="__('Nomor Telepon')" />
                            <x-text-input id="phone_number" class="block mt-1 w-full" type="text"
                                name="phone_number" :value="old('phone_number')" />
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="school_class_id" :value="__('Kelas')" />
                            <select id="school_class_id" name="school_class_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Kelas</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('school_class_id')" class="mt-2" />
                            @if ($classes->isEmpty())
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">Tidak ada kelas ditemukan. Harap
                                    tambahkan kelas terlebih dahulu.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan Siswa') }}
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
