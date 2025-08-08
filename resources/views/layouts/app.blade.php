<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en" x-data="mainApp()" x-init="init()" :class="{ 'dark': darkMode, }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $globalSettings->school_name ?? config('app.name', 'SekolahApp') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="favicon-32x32.png" type="image/x-icon">

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
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

</head>

<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors">
    @include('admin.dashboard.partials.header')
    @include('admin.dashboard.partials.sidebar')
    <main class="lg:ml-64 p-4 mt-4" id="">
        {{ $slot }}
    </main>
    <script>
        function previewNewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('image');
                output.src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function mainApp() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark',
                sidebarOpen: false,
                sidebarOpen: window.innerWidth >= 1024,
                init() {
                    this.updateTheme();
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.sidebarOpen = true;
                        }
                    });
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
                Swal.fire({
                    title: 'Tandai semua sudah dibaca?',
                    text: "Anda tidak dapat mengurungkan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, tandai!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('notifications.markAllAsReadBulk') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.querySelectorAll('.notification-item').forEach(item => {
                                        const parentRow = item.closest('.notification-row');
                                        // --- Hapus SEMUA kelas yang menunjukkan status belum dibaca dari TR ---
                                        parentRow.classList.remove(
                                            'bg-blue-50', 'dark:bg-blue-900',
                                            'border', 'border-blue-300', 'dark:border-blue-700',
                                            'font-bold'
                                        );
                                        parentRow.classList.remove('text-blue-800',
                                            'dark:text-blue-200'); // Hapus dari TR juga
                                        parentRow.classList.remove('text-gray-900',
                                            'dark:text-white'); // Hapus dari TR juga (dari bold)

                                        // --- Tambahkan kelas untuk status sudah dibaca ke TR ---
                                        parentRow.classList.add('bg-gray-100', 'dark:bg-gray-800');
                                        parentRow.classList.add('text-gray-700',
                                            'dark:text-gray-300'); // Atur warna teks default ke TR

                                        // --- Hapus SEMUA kelas yang menunjukkan status belum dibaca dari <a> ---
                                        item.classList.remove('text-blue-800', 'dark:text-blue-200');

                                        // --- Tambahkan kelas untuk status sudah dibaca ke <a> ---
                                        item.classList.add('text-gray-700', 'dark:text-gray-300');

                                        const newBadge = item.querySelector('.bg-blue-600');
                                        if (newBadge) {
                                            newBadge.remove(); // Hapus elemen badge
                                        }
                                    });

                                    const countElement = document.getElementById('unread-notifications-count');
                                    const responsiveCountElement = document.getElementById(
                                        'responsive-unread-notifications-count');

                                    if (countElement) {
                                        countElement.textContent = '0';
                                        countElement.classList.add('hidden');
                                    }
                                    if (responsiveCountElement) {
                                        responsiveCountElement.textContent = '0';
                                        responsiveCountElement.classList.add('hidden');
                                    }

                                    fetchUnreadNotificationsCount
                                        (); // Panggil fungsi fetchUnreadNotificationsCount() untuk verifikasi akhir dari server

                                    Swal.fire(
                                        'Ditandai!',
                                        'Semua notifikasi telah ditandai sudah dibaca.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        data.message || 'Tidak dapat menandai semua notifikasi sudah dibaca.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                console.error('Error marking all as read:', error);
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menandai notifikasi.',
                                    'error'
                                );
                            });
                    }
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

    <script>
        document.addEventListener('alpine:init', () => {
            new TomSelect('#school_class_id', {
                placeholder: 'Cari kelas...',
                allowEmptyOption: true,
            });
            Alpine.store('ui', {
                sidebarOpen: false,

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },
                closeSidebar() {
                    this.sidebarOpen = false;
                },
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const previewContainer = document.createElement('div');
            previewContainer.classList.add('mb-2');
            imageInput.parentNode.insertBefore(previewContainer, imageInput);

            imageInput.addEventListener('change', function() {
                previewContainer.innerHTML = ''; // Kosongkan sebelumnya

                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Preview Gambar Baru';
                        img.classList.add('w-64', 'h-auto', 'rounded-lg', 'mt-2');
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
</body>

</html>
