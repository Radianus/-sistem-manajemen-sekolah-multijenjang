<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesan Internal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">
                            {{ $tab === 'sent' ? 'Pesan Terkirim' : 'Kotak Masuk' }}
                        </h3>
                        <a href="{{ route('messages.create') }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Kirim Pesan Baru
                        </a>
                    </div>

                    <div class="mb-4 flex space-x-4 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('messages.index', ['tab' => 'inbox']) }}"
                            class="py-2 px-4 -mb-px border-b-2 {{ $tab === 'inbox' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} dark:text-gray-400 dark:hover:text-gray-200">
                            Kotak Masuk
                        </a>
                        <a href="{{ route('messages.index', ['tab' => 'sent']) }}"
                            class="py-2 px-4 -mb-px border-b-2 {{ $tab === 'sent' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }} dark:text-gray-400 dark:hover:text-gray-200">
                            Terkirim
                        </a>
                    </div>

                    {{-- Flash messages will be handled by SweetAlert in app.blade.php --}}

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">
                                        {{ $tab === 'sent' ? 'Penerima' : 'Pengirim' }}
                                    </th>
                                    <th scope="col" class="py-3 px-6">Subjek</th>
                                    <th scope="col" class="py-3 px-6">Isi Pesan</th>
                                    <th scope="col" class="py-3 px-6">Waktu</th>
                                    <th scope="col" class="py-3 px-6">Status</th>
                                    <th scope="col" class="py-3 px-6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $message)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $message->read_at ? '' : 'font-bold text-gray-900 dark:text-white' }}">
                                        <td class="py-4 px-6">
                                            {{ $tab === 'sent' ? $message->receiver->name ?? 'N/A' : $message->sender->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $message->subject ?? '(Tanpa Subjek)' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ Str::limit($message->content, 70) }}
                                        </td>
                                        <td class="py-4 px-6">
                                            {{ $message->created_at->diffForHumans() }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($message->read_at)
                                                <span
                                                    class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Dibaca</span>
                                            @else
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Belum
                                                    Dibaca</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 flex items-center space-x-3">
                                            <a href="{{ route('messages.show', $message) }}"
                                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Lihat</a>
                                            <a href="{{ route('messages.reply', $message) }}"
                                                class="font-medium text-green-600 dark:text-green-500 hover:underline">Balas</a>
                                            <form action="{{ route('messages.destroy', $message) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="6"
                                            class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada pesan di
                                            {{ $tab === 'sent' ? 'Kotak Terkirim' : 'Kotak Masuk' }}.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
