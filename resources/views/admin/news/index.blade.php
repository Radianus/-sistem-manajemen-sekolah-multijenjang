<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Berita') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Berita</h3>
                        @if (Auth::user()->hasRole('admin_sekolah') || Auth::user()->hasRole('guru'))
                            <a href="{{ route('admin.news.create') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Buat Berita Baru
                            </a>
                        @endif
                    </div>
                    @if ($news->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada berita ditemukan.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">ID</th>
                                        <th scope="col" class="py-3 px-6">Judul</th>
                                        <th scope="col" class="py-3 px-6">Penulis</th>
                                        <th scope="col" class="py-3 px-6">Status Publikasi</th>
                                        <th scope="col" class="py-3 px-6">Dibuat Pada</th>
                                        <th scope="col" class="py-3 px-6">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($news as $item)
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="row"
                                                class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $item->id }}
                                            </th>
                                            <td class="py-4 px-6">
                                                {{ $item->title }}
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $item->author->name ?? 'N/A' }}
                                            </td>
                                            <td class="py-4 px-6">
                                                @if ($item->published_at)
                                                    <span
                                                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">Published</span>
                                                @else
                                                    <span
                                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">Draft</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                {{ $item->created_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="py-4 px-6 flex items-center space-x-3">
                                                @if (Auth::user()->hasRole('admin_sekolah') || (Auth::user()->hasRole('guru') && Auth::id() == $item->user_id))
                                                    <a href="{{ route('admin.news.edit', $item) }}"
                                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                                    <form action="{{ route('admin.news.destroy', $item) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-600">Tidak ada aksi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $news->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
