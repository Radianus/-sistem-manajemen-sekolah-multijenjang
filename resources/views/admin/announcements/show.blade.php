<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto px-6">
            {{-- Header Custom --}}
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    📢 {{ $announcement->title }}
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Diterbitkan:
                    <strong>
                        {{ $announcement->published_at ? $announcement->published_at->format('d M Y, H:i') : 'Segera' }}
                    </strong>
                    @if ($announcement->expires_at)
                        &nbsp;s/d <strong>{{ $announcement->expires_at->format('d M Y, H:i') }}</strong>
                    @endif
                </p>
            </div>

            {{-- Info Tambahan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-300 mb-6">
                <div>
                    <span class="text-gray-500">🎯 Target:</span><br>
                    <strong>{{ $announcement->target_roles ?? 'Semua' }}</strong>
                </div>
                <div>
                    <span class="text-gray-500">✍️ Dibuat oleh:</span><br>
                    <strong>{{ $announcement->creator->name ?? 'N/A' }}</strong>
                </div>
            </div>

            {{-- Isi Pengumuman --}}
            <div
                class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 leading-relaxed border border-gray-200 dark:border-gray-700 rounded-lg p-5">
                {!! nl2br(e($announcement->content)) !!}
            </div>

            {{-- Aksi --}}
            <div class="flex justify-between items-center mt-8">
                <a href="{{ route('admin.announcements.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-white transition">
                    ← Kembali ke Daftar
                </a>

                @if (Auth::user()->hasRole('admin_sekolah') || Auth::id() == $announcement->created_by_user_id)
                    <a href="{{ route('admin.announcements.edit', $announcement) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition">
                        ✏️ Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
