<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" x-data="mainApp()" x-init="init()" :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'SekolahApp') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</head>

<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors">
    @include('admin.dashboard.partials.header')

    @include('admin.dashboard.partials.sidebar')

    {{-- Main Content --}}
    <main class="lg:ml-64 p-4 mt-4">
        {{ $slot }}
    </main>
    <script>
        function mainApp() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark',
                sidebarOpen: false,

                init() {
                    this.updateTheme();
                },

                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
                    this.updateTheme();
                },

                updateTheme() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                current: localStorage.getItem('theme') || 'light',
                toggle() {
                    this.current = this.current === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('theme', this.current);
                    document.documentElement.classList.toggle('dark', this.current === 'dark');
                }
            });

            // Set theme on load
            document.documentElement.classList.toggle('dark', Alpine.store('theme').current === 'dark');
        });
    </script>
    @auth
        <script>
            function markAllAsRead() {
                fetch('/notifications/mark-all-as-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Kosongkan notifikasi atau tandai semua sebagai dibaca di DOM
                            document.getElementById('notification-badge').innerText = '0';

                            // Opsional: Sembunyikan badge kalau 0
                            document.getElementById('notification-badge').style.display = 'none';

                            // Hapus/ubah tampilan item notifikasi
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                            });

                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Gagal menandai semua notifikasi sebagai dibaca:', error);
                    });
            }

            function fetchUnreadNotificationsCount() {
                fetch('{{ route('notifications.unreadCount') }}')
                    .then(response => response.json())
                    .then(data => {
                        const countElement = document.getElementById('unread-notifications-count');
                        const responsiveCountElement = document.getElementById('responsive-unread-notifications-count');
                        if (data.count > 0) {
                            countElement.textContent = data.count;
                            countElement.classList.remove('hidden');
                            responsiveCountElement.textContent = data.count;
                            responsiveCountElement.classList.remove('hidden');
                        } else {
                            countElement.classList.add('hidden');
                            responsiveCountElement.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error fetching unread notifications count:', error));
            }

            // Fetch on page load
            document.addEventListener('DOMContentLoaded', fetchUnreadNotificationsCount);

            // Optionally, fetch every X seconds for real-time update (e.g., every 30 seconds)
            setInterval(fetchUnreadNotificationsCount, 30000);
        </script>
    @endauth
    @auth
        <script>
            document.querySelectorAll('.notification-item').forEach(item => {
                if (!item.classList.contains('bg-gray-100')) { // Check if it's currently unread (not gray)
                    item.addEventListener('click', function(event) {
                        const notificationId = item.dataset.notificationId;
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
                                        // Update UI
                                        item.classList.remove('bg-blue-100', 'dark:bg-blue-900',
                                            'text-blue-800', 'dark:text-blue-200', 'border',
                                            'border-blue-300', 'dark:border-blue-700');
                                        item.classList.add('bg-gray-100', 'dark:bg-gray-700',
                                            'text-gray-700', 'dark:text-gray-300');
                                        item.querySelector('.bg-blue-600')?.classList.add(
                                            'hidden'); // Sembunyikan label 'Baru'
                                        fetchUnreadNotificationsCount
                                            (); // Update the count in navbar (didefinisikan di app.blade.php)
                                    }
                                })
                                .catch(error => console.error('Error marking notification as read:', error));
                        }
                    });
                }
            });
        </script>
    @endauth

</body>

</html>
