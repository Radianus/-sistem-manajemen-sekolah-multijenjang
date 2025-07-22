<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kelas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Kelas</h3>
                        <a href="{{ route('admin.classes.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Kelas
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.103l-2.651 2.651a1.2 1.2 0 11-1.697-1.697L8.303 9.406 5.652 6.755a1.2 1.2 0 011.697-1.697L10 7.709l2.651-2.651a1.2 1.2 0 011.697 1.697L11.697 9.406l2.651 2.651a1.2 1.2 0 010 1.697z" />
                                </svg>
                            </span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 01-1.697 0L10 11.103l-2.651 2.651a1.2 1.2 0 11-1.697-1.697L8.303 9.406 5.652 6.755a1.2 1.2 0 011.697-1.697L10 7.709l2.651-2.651a1.2 1.2 0 011.697 1.697L11.697 9.406l2.651 2.651a1.2 1.2 0 010 1.697z" />
                                </svg>
                            </span>
                        </div>
                    @endif

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">ID</th>
                                    <th scope="col" class="py-3 px-6">Nama Kelas</th>
                                    <th scope="col" class="py-3 px-6">Jenjang</th>
                                    <th scope="col" class="py-3 px-6">Tingkat</th>
                                    <th scope="col" class="py-3 px-6">Tahun Ajaran</th>
                                    <th scope="col" class="py-3 px-6">Wali Kelas</th>
                                    <th scope="col" class="py-3 px-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classes as $class)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th scope="row"
                                            class="py-4 px-6 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $class->id }}
                                        </th>
                                        <td class="py-4 px-6">
                                            {{ $class->name }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $class->level ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $class->grade_level ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $class->academic_year ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $class->homeroomTeacher->name ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 flex items-center space-x-3">
                                            <a href="{{ route('admin.classes.edit', $class) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                            <form action="{{ route('admin.classes.destroy', $class) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini? Ini tidak akan menghapus siswa yang terkait, hanya akan mengatur kelas siswa menjadi null.');">
                                                {{-- Updated warning --}}
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="7"
                                            class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada kelas ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $classes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
