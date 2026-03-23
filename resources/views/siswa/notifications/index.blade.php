@extends('layouts.siswa-sidebar')

@section('page-title', 'Notifikasi')
@section('page-description', 'Kelola semua notifikasi Anda')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi Siswa</h3>
                        <p class="text-sm text-gray-600 mt-1">Kelola semua notifikasi akademik dan aktivitas kelas</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if ($unreadCount > 0)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ $unreadCount }} Belum Dibaca
                            </span>
                        @endif
                        @if ($notifications->count() > 0)
                            <button onclick="markAllAsRead()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                                <i class="fas fa-check-double mr-2"></i>
                                Tandai Semua Dibaca
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <div
                        class="p-6 hover:bg-gray-50 transition-colors duration-200 {{ !$notification->is_read ? 'bg-green-50 border-l-4 border-green-400' : '' }}">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->badge_color }}">
                                    <i class="{{ $notification->icon }} text-sm"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4
                                        class="text-sm font-semibold text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                        {{ $notification->title }}
                                    </h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if (!$notification->is_read)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Baru
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ $notification->message }}
                                </p>

                                <!-- Actions -->
                                <div class="mt-3 flex items-center space-x-3">
                                    @if (!$notification->is_read)
                                        <button onclick="markAsRead({{ $notification->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-green-600 bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors duration-200">
                                            <i class="fas fa-check mr-1"></i>
                                            Tandai Dibaca
                                        </button>
                                    @endif

                                    <button onclick="deleteNotification({{ $notification->id }})"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                                        <i class="fas fa-trash mr-1"></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Notifikasi</h3>
                        <p class="text-gray-600">Belum ada notifikasi yang masuk. Notifikasi akan muncul di sini ketika ada
                            aktivitas akademik atau informasi dari guru.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $notifications->firstItem() ?? 0 }} sampai
                            {{ $notifications->lastItem() ?? 0 }} dari {{ $notifications->total() }} notifikasi
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function markAsRead(notificationId) {
                fetch(`/siswa/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload page to update UI
                            location.reload();
                        } else {
                            alert('Gagal menandai notifikasi sebagai dibaca');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menandai notifikasi');
                    });
            }

            function markAllAsRead() {
                if (confirm('Apakah Anda yakin ingin menandai semua notifikasi sebagai dibaca?')) {
                    fetch('/siswa/notifications/mark-all-as-read', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload page to update UI
                                location.reload();
                            } else {
                                alert('Gagal menandai semua notifikasi sebagai dibaca');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menandai notifikasi');
                        });
                }
            }

            function deleteNotification(notificationId) {
                if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
                    // Show loading state
                    const button = event.target;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
                    button.disabled = true;

                    fetch(`/siswa/notifications/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                // Reload page to update UI
                                location.reload();
                            } else {
                                alert('Gagal menghapus notifikasi: ' + (data.message || 'Unknown error'));
                            }
                        })
                        .catch(error => {
                            console.error('Error details:', error);
                            alert('Terjadi kesalahan saat menghapus notifikasi: ' + error.message);

                            // Restore button state
                            button.innerHTML = originalText;
                            button.disabled = false;
                        });
                }
            }
        </script>
    @endpush
@endsection
