<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Balas Pesan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Balas:
                        {{ $message->subject ?? '(Tanpa Subjek)' }}</h3>

                    <div
                        class="mb-6 p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-700">
                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                            Dari: <span class="font-semibold">{{ $message->sender->name ?? 'N/A' }}</span>
                            Kepada: <span class="font-semibold">{{ $message->receiver->name ?? 'N/A' }}</span>
                            Dikirim Pada: {{ $message->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                            {!! nl2br(e($message->content)) !!}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('messages.store') }}">
                        @csrf
                        <input type="hidden" name="parent_message_id" value="{{ $message->id }}">
                        <input type="hidden" name="receiver_id"
                            value="{{ $message->sender_id === Auth::id() ? $message->receiver_id : $message->sender_id }}">
                        <input type="hidden" name="subject" value="Re: {{ $message->subject ?? '(Tanpa Subjek)' }}">

                        <div class="mb-6">
                            <x-input-label for="content" :value="__('Isi Balasan Anda')" />
                            <textarea id="content" name="content"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Kirim Balasan') }}
                            </x-primary-button>
                            <a href="{{ route('messages.show', $message) }}"
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
