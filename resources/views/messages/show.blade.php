<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lihat Pesan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Subjek:
                        {{ $message->subject ?? '(Tanpa Subjek)' }}</h3>

                    <div
                        class="mb-4 p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            Dari: <span class="font-semibold">{{ $message->sender->name ?? 'Pengguna Tidak Ditemukan' }}
                                ({{ $message->sender->email ?? 'N/A' }})</span>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            Kepada: <span
                                class="font-semibold">{{ $message->receiver->name ?? 'Pengguna Tidak Ditemukan' }}
                                ({{ $message->receiver->email ?? 'N/A' }})</span>
                        </div>
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            Dikirim Pada: {{ $message->created_at->format('d M Y, H:i') }}
                        </div>
                        @if ($message->read_at)
                            <div class="text-sm text-gray-700 dark:text-gray-300">
                                Dibaca Pada: {{ $message->read_at->format('d M Y, H:i') }}
                            </div>
                        @else
                            <div class="text-sm text-blue-600 dark:text-blue-400">
                                Status: Belum Dibaca
                            </div>
                        @endif
                    </div>

                    <div
                        class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 mt-6 p-4 border border-gray-300 dark:border-gray-700 rounded-md">
                        {!! nl2br(e($message->content)) !!}
                    </div>

                    {{-- TAMPILKAN LAMPIRAN FILE --}}
                    @if ($message->attachments->isNotEmpty())
                        <div
                            class="mt-4 p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700">
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2">Lampiran File:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                                @foreach ($message->attachments as $attachment)
                                    <li>
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                            class="text-blue-500 hover:underline">
                                            {{ $attachment->file_name }}
                                            ({{ number_format($attachment->file_size / 1024, 2) }} KB)
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('messages.index', ['tab' => 'inbox']) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kembali ke Kotak Masuk') }}
                        </a>
                        <a href="{{ route('messages.reply', $message) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Balas Pesan') }}
                        </a>
                    </div>

                    @if ($replies->isNotEmpty())
                        <h4 class="font-semibold text-xl text-gray-800 dark:text-gray-200 mt-8 mb-4">Balasan:</h4>
                        <div class="space-y-4">
                            @foreach ($replies as $reply)
                                <div
                                    class="p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700 {{ $reply->sender_id === Auth::id() ? 'ml-auto text-right' : '' }}">
                                    <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                        Dari: <span class="font-semibold">{{ $reply->sender->name ?? 'N/A' }}</span>
                                        Kepada: <span
                                            class="font-semibold">{{ $reply->receiver->name ?? 'N/A' }}</span>
                                        ({{ $reply->created_at->diffForHumans() }})
                                    </div>
                                    <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                                        {!! nl2br(e($reply->content)) !!}
                                    </div>
                                    @if ($reply->attachments->isNotEmpty())
                                        <div class="mt-2 text-left">
                                            <h5 class="font-semibold text-sm text-gray-900 dark:text-gray-100 mb-1">
                                                Lampiran Balasan:</h5>
                                            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300">
                                                @foreach ($reply->attachments as $attachment)
                                                    <li>
                                                        <a href="{{ Storage::url($attachment->file_path) }}"
                                                            target="_blank" class="text-blue-500 hover:underline">
                                                            {{ $attachment->file_name }}
                                                            ({{ number_format($attachment->file_size / 1024, 2) }} KB)
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
