<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Acara Kalender') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Edit Acara:
                        {{ $calendarEvent->title }}</h3>

                    <form method="POST" action="{{ route('admin.calendar_events.update', $calendarEvent) }}">
                        @csrf
                        @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Acara')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $calendarEvent->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="3">{{ old('description', $calendarEvent->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="event_type" :value="__('Jenis Acara')" />
                            <select id="event_type" name="event_type" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Pilih Jenis Acara</option>
                                @foreach ($eventTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ old('event_type', $calendarEvent->event_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('event_type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date"
                                :value="old('start_date', $calendarEvent->start_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="end_date" :value="__('Tanggal Selesai (Opsional)')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date"
                                :value="old(
                                    'end_date',
                                    $calendarEvent->end_date ? $calendarEvent->end_date->format('Y-m-d') : '',
                                )" />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="start_time" :value="__('Waktu Mulai (Opsional)')" />
                            <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time"
                                :value="old(
                                    'start_time',
                                    $calendarEvent->start_time ? $calendarEvent->start_time->format('H:i') : '',
                                )" />
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="end_time" :value="__('Waktu Selesai (Opsional)')" />
                            <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time"
                                :value="old(
                                    'end_time',
                                    $calendarEvent->end_time ? $calendarEvent->end_time->format('H:i') : '',
                                )" />
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="location" :value="__('Lokasi (Opsional)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                :value="old('location', $calendarEvent->location)" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="target_roles" :value="__('Ditargetkan Untuk Peran')" />
                            <select id="target_roles" name="target_roles[]" multiple
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                size="5">
                                @foreach ($roles as $roleName)
                                    <option value="{{ $roleName }}"
                                        {{ in_array($roleName, old('target_roles', $selectedRoles)) ? 'selected' : '' }}>
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
                                {{ __('Perbarui Acara') }}
                            </x-primary-button>
                            <a href="{{ route('admin.calendar_events.index') }}"
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
