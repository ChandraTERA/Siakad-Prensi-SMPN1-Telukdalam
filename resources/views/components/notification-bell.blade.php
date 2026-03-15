<!-- Simple Notification Bell Component -->
<div class="relative">
    <!-- Notification Bell Button -->
    <button onclick="toggleNotificationDropdown()"
        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-bell text-lg"></i>

        <!-- Unread Count Badge -->
        <span id="notification-count"
            class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full hidden">
        </span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                <div class="flex items-center space-x-2">
                    <span id="unread-text" class="text-xs text-red-600 font-medium hidden"></span>
                    <button onclick="markAllAsRead()" id="mark-all-btn"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium hidden">
                        Tandai Semua Dibaca
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="notification-loading" class="p-4 text-center">
            <i class="fas fa-spinner fa-spin text-gray-400"></i>
            <p class="text-sm text-gray-500 mt-2">Memuat notifikasi...</p>
        </div>

        <!-- Notifications List -->
        <div id="notifications-list" class="max-h-96 overflow-y-auto hidden">
            <!-- Notifications will be loaded here -->
        </div>

        <!-- Empty State -->
        <div id="empty-notifications" class="p-6 text-center hidden">
            <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
            <p class="text-sm text-gray-500">Tidak ada notifikasi</p>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <a href="/{{ Auth::user()->role }}/notifications"
                class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
    let notificationDropdownOpen = false;

    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        const countBadge = document.getElementById('notification-count');

        if (notificationDropdownOpen) {
            dropdown.classList.add('hidden');
            notificationDropdownOpen = false;
        } else {
            dropdown.classList.remove('hidden');
            notificationDropdownOpen = true;
            loadNotifications();
        }
    }

    function loadNotifications() {
        const loading = document.getElementById('notification-loading');
        const list = document.getElementById('notifications-list');
        const empty = document.getElementById('empty-notifications');

        loading.classList.remove('hidden');
        list.classList.add('hidden');
        empty.classList.add('hidden');

        fetch('/{{ Auth::user()->role }}/notifications/recent')
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');

                if (data.notifications && data.notifications.length > 0) {
                    displayNotifications(data.notifications);
                    list.classList.remove('hidden');

                    // Update unread count
                    updateUnreadCount(data.unread_count);
                } else {
                    empty.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                loading.classList.add('hidden');
                empty.classList.remove('hidden');
            });
    }

    function displayNotifications(notifications) {
        const list = document.getElementById('notifications-list');
        list.innerHTML = '';

        notifications.forEach(notification => {
            const notificationElement = createNotificationElement(notification);
            list.appendChild(notificationElement);
        });
    }

    function createNotificationElement(notification) {
        const div = document.createElement('div');
        div.className =
            `px-4 py-3 hover:bg-gray-50 border-b border-gray-100 ${!notification.is_read ? 'bg-blue-50' : ''}`;

        const iconClass = getNotificationIcon(notification.type);
        const iconColor = getNotificationColor(notification.type);

        div.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 rounded-full flex items-center justify-center ${iconColor}">
                    <i class="text-sm ${iconClass}"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-900 ${!notification.is_read ? 'font-bold' : ''}">
                        ${notification.title}
                    </h4>
                    <span class="text-xs text-gray-500">
                        ${new Date(notification.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                    </span>
                </div>
                <p class="mt-1 text-sm text-gray-600">${notification.message}</p>
                <div class="mt-2 flex items-center space-x-2">
                    ${!notification.is_read ? `
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Baru
                        </span>
                        <button onclick="markAsRead(${notification.id})" 
                                class="text-xs text-green-600 hover:text-green-800 font-medium">
                            Tandai Dibaca
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;

        return div;
    }

    function getNotificationIcon(type) {
        const icons = {
            'presensi_verification': 'fas fa-check-circle',
            'materi_upload': 'fas fa-file-alt',
            'tugas_upload': 'fas fa-tasks',
            'presensi_open': 'fas fa-unlock',
            'presensi_close': 'fas fa-lock',
            'default': 'fas fa-bell'
        };
        return icons[type] || icons.default;
    }

    function getNotificationColor(type) {
        const colors = {
            'presensi_verification': 'bg-green-100 text-green-600',
            'materi_upload': 'bg-blue-100 text-blue-600',
            'tugas_upload': 'bg-purple-100 text-purple-600',
            'presensi_open': 'bg-green-100 text-green-600',
            'presensi_close': 'bg-red-100 text-red-600',
            'default': 'bg-gray-100 text-gray-600'
        };
        return colors[type] || colors.default;
    }

    function markAsRead(notificationId) {
        fetch(`/{{ Auth::user()->role }}/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications(); // Reload notifications
                }
            });
    }

    function markAllAsRead() {
        fetch('/{{ Auth::user()->role }}/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications(); // Reload notifications
                }
            });
    }

    function updateUnreadCount(count) {
        const countBadge = document.getElementById('notification-count');
        const unreadText = document.getElementById('unread-text');
        const markAllBtn = document.getElementById('mark-all-btn');

        if (count > 0) {
            countBadge.textContent = count;
            countBadge.classList.remove('hidden');
            unreadText.textContent = count + ' baru';
            unreadText.classList.remove('hidden');
            markAllBtn.classList.remove('hidden');
        } else {
            countBadge.classList.add('hidden');
            unreadText.classList.add('hidden');
            markAllBtn.classList.add('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notification-dropdown');
        const button = event.target.closest('button');

        if (!dropdown.contains(event.target) && button !== event.target) {
            dropdown.classList.add('hidden');
            notificationDropdownOpen = false;
        }
    });

    // Load initial notification count
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/{{ Auth::user()->role }}/notifications/recent')
            .then(response => response.json())
            .then(data => {
                updateUnreadCount(data.unread_count);
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
    });
</script>
