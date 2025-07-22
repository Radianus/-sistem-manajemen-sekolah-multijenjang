<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex  flex-col sm:flex-row sm:justify-between sm:items-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Daftar Notifikasi</h3>
                        @if (!$notifications->isEmpty())
                            <button type="button" onclick="markAllAsRead()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                Tandai Semua Sudah Dibaca
                            </button>
                        @endif
                    </div>
                    @if ($notifications->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Anda tidak memiliki notifikasi saat ini.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($notifications as $notification)
                                <a href="{{ $notification->link ?? '#' }}"
                                    class="block p-4 rounded-lg shadow-sm transition-all duration-200 
                                    {{ $notification->read_at
                                        ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                        : 'bg-blue-50 dark:bg-blue-900 text-blue-800 dark:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-800' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-semibold text-lg">{{ $notification->title }}</h4>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>

                                    </div>
                                    <p class="text-sm">{{ $notification->message }}</p>
                                    @if (!$notification->read_at)
                                        <span
                                            class="mt-2 inline-block bg-blue-200 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-blue-800 dark:text-blue-200">Baru</span>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
