<!-- Header -->
<header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800 relative z-40">
    <!-- මේ මාතෘකාව පිටුවෙන් පිටුවට වෙනස් වෙනවා නම්, අපිට මේක variable එකක් විදිහට ගන්නත් පුළුවන්. දැනට 'Dashboard' කියලා තියමු -->
    <h2 class="text-xl font-bold">Gym Portal</h2>
    
    <div class="flex items-center gap-6">
        <!-- 🔴 Notification Bell 🔴 -->
        <div class="relative cursor-pointer flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 transition shadow-lg border border-gray-700" onclick="toggleNotifications()">
            <i class="fa-solid fa-bell text-gray-300"></i>
            <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
            
            <!-- Notification Dropdown -->
            <div id="notif-dropdown" class="absolute right-0 top-12 w-80 bg-panelBg border border-gray-700 rounded-xl shadow-2xl hidden z-50 overflow-hidden text-left cursor-default">
                <div class="flex justify-between items-center px-4 py-3 border-b border-gray-700 bg-darkBg">
                    <h4 class="font-bold text-white">Notifications</h4>
                    <button onclick="markAsRead(event)" class="text-xs text-brandBlue hover:underline">Mark all read</button>
                </div>
                <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-800">
                    <!-- මැසේජ් ටික මෙතනට එනවා -->
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="flex items-center gap-3 cursor-pointer">
            <div class="w-10 h-10 rounded-full bg-gray-700 border-2 border-gray-500 flex items-center justify-center text-white font-bold"><i class="fa-solid fa-user"></i></div>
        </div>
    </div>
</header>

<!-- Notification Scripts -->
<script>
    // Header එක හැම තැනම තියෙන නිසා, Script එකත් මෙතනම තියෙන එක ලේසියි
    const globalMyToken = localStorage.getItem('gym_super_admin_token') || localStorage.getItem('gym_owner_token');

    document.addEventListener('DOMContentLoaded', () => {
        loadNotifications(); 
    });

    function loadNotifications() {
        if(!globalMyToken) return;

        fetch('/api/notifications', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + globalMyToken }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                let badge = document.getElementById('notif-badge');
                if(data.unread_count > 0) {
                    badge.innerText = data.unread_count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                let list = document.getElementById('notif-list');
                let html = '';
                if(data.notifications.length > 0) {
                    data.notifications.forEach(n => {
                        let bg = n.is_read ? 'bg-panelBg' : 'bg-gray-800/60';
                        let dot = n.is_read ? '' : '<span class="w-2 h-2 rounded-full bg-blue-500 absolute top-4 right-4"></span>';
                        
                        html += `
                            <div class="px-4 py-3 ${bg} hover:bg-gray-700 transition relative">
                                ${dot}
                                <p class="text-sm font-bold text-white mb-1">${n.title}</p>
                                <p class="text-xs text-gray-400 leading-relaxed">${n.message}</p>
                                <p class="text-[10px] text-gray-500 mt-2">${new Date(n.created_at).toLocaleString()}</p>
                            </div>
                        `;
                    });
                } else {
                    html = '<p class="text-xs text-gray-500 text-center py-6">No new notifications.</p>';
                }
                list.innerHTML = html;
            }
        });
    }

    function toggleNotifications() {
        document.getElementById('notif-dropdown').classList.toggle('hidden');
    }

    function markAsRead(e) {
        e.stopPropagation(); 
        fetch('/api/notifications/mark-read', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + globalMyToken }
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                loadNotifications(); 
            }
        });
    }
</script>