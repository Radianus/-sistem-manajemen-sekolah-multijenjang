<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Slider') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Slider</h3>
                        @if (Auth::user()->hasRole('admin_sekolah'))
                            <a href="{{ route('admin.hero_sliders.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Slider Baru
                            </a>
                        @endif
                    </div>

                    @if ($sliders->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada slider ditemukan.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Order</th>
                                        <th scope="col" class="py-3 px-6">Judul</th>
                                        <th scope="col" class="py-3 px-6">Gambar</th>
                                        <th scope="col" class="py-3 px-6">Status</th>
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sliders as $slider)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td
                                                class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $slider->order }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $slider->title }}
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($slider->image_path)
                                                    <img src="{{ Storage::url($slider->image_path) }}"
                                                        alt="{{ $slider->title }}" class="w-20 h-auto">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($slider->is_active)
                                                    <span
                                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Aktif</span>
                                                @else
                                                    <span
                                                        class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">Tidak
                                                        Aktif</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 flex items-center space-x-3">
                                                <a href="{{ route('admin.hero_sliders.edit', $slider) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-semibold uppercase rounded-md hover:bg-blue-600 transition">
                                                    ✏️ Edit
                                                </a>
                                                <form action="{{ route('admin.hero_sliders.destroy', $slider) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus slider ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold uppercase rounded-md hover:bg-red-700 transition">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $sliders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
