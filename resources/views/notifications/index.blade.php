<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifikasi Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Daftar Notifikasi
                        </h3>
                        @if (!$notifications->isEmpty())
                            <button type="button" onclick="markAllAsRead()"
                                class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition w-full sm:w-auto">
                                Tandai Semua Sudah Dibaca
                            </button>
                        @endif
                    </div>

                    @if ($notifications->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Anda tidak memiliki notifikasi saat ini.</p>
                    @else
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Notifikasi</th>
                                        <th scope="col" class="py-3 px-6">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($notifications as $notification)
                                        <tr
                                            class="notification-row border-b mb-2 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 
                                                    {{ $notification->read_at
                                                        ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                                                        : 'bg-blue-50 dark:bg-blue-900 text-blue-800 dark:text-blue-200 font-bold' }}">
                                            <td class="py-0 px-0 mb-2">
                                                <a href="{{ $notification->link ?? '#' }}"
                                                    class="notification-item block p-4 rounded-lg shadow-sm transition-all duration-200 cursor-pointer w-full h-full
                                                   {{ $notification->read_at ? 'text-gray-700 dark:text-gray-300' : 'text-blue-800 dark:text-blue-200' }}"
                                                    data-notification-id="{{ $notification->id }}">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h4 class="font-semibold text-lg">{{ $notification->title }}
                                                        </h4>
                                                        <span
                                                            class="text-sm text-gray-500 dark:text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm">{{ $notification->message }}</p>
                                                    @if (!$notification->read_at)
                                                        <span
                                                            class="mt-2 inline-block bg-blue-600 text-white text-xs font-medium px-2.5 py-0.5 rounded-full">Baru</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $notification->created_at->format('d-m-Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td colspan="2"
                                                class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                                Tidak ada notifikasi saat ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @auth
        <script>
            document.querySelectorAll('.notification-item').forEach(item => {
                if (!item.closest('.notification-row').classList.contains('bg-gray-100')) {
                    item.addEventListener('click', function(event) {
                        event.preventDefault();
                        const notificationId = item.dataset.notificationId;
                        const notificationLink = item.href;
                        const parentRow = item.closest('.notification-row');

                        if (notificationId) {
                            fetch('{{ url('/notifications') }}/' + notificationId + '/read', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        parentRow.classList.remove('bg-blue-50', 'dark:bg-blue-900',
                                            'text-blue-800', 'dark:text-blue-200', 'border',
                                            'border-blue-300', 'dark:border-blue-700', 'font-bold',
                                            'text-gray-900', 'dark:text-white');
                                        parentRow.classList.add('bg-gray-100', 'dark:bg-gray-800',
                                            'text-gray-700', 'dark:text-gray-300');
                                        item.classList.remove('text-blue-800', 'dark:text-blue-200');
                                        item.classList.add('text-gray-700', 'dark:text-gray-300');
                                        const newBadge = item.querySelector('.bg-blue-600');
                                        if (newBadge) {
                                            newBadge.remove();
                                        }
                                        if (typeof fetchUnreadNotificationsCount === 'function') {
                                            fetchUnreadNotificationsCount();
                                        } else {
                                            console.warn(
                                                'fetchUnreadNotificationsCount is not defined. Navbar count may not update.'
                                            );
                                        }
                                        if (notificationLink && notificationLink !== '#') {
                                            window.location.href = notificationLink;
                                        }
                                    }
                                })
                                .catch(error => console.error('Error marking notification as read:', error));
                        }
                    });
                }
            });
        </script>
    @endauth
</x-app-layout>
