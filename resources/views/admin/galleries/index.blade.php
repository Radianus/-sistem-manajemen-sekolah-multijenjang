<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Galeri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-end mb-4">
                    <a href="{{ route('admin.galleries.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Tambah Gambar
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Gambar
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Pengunggah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($gallery as $item)
                                <tr>
                                    <td class="py-4 px-6">
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->title }}"
                                            class="w-32 h-32 object-cover rounded">
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $item->title }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $item->uploader->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-4 px-6 align-middle">
                                        <div class="flex items-center space-x-2">
                                            @if (Auth::user()->hasRole('admin_sekolah') || (Auth::user()->hasRole('guru') && Auth::id() == $item->user_id))
                                                <a href="{{ route('admin.galleries.edit', $item) }}"
                                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                                                    ✏️ Edit
                                                </a>
                                                <form action="{{ route('admin.galleries.destroy', $item) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600 italic">Tidak ada
                                                    aksi</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        Tidak ada data galeri yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
