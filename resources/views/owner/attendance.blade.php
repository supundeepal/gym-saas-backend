<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - Gym Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandBlue: '#3b82f6' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-panelBg flex flex-col justify-between h-full border-r border-gray-800">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 cursor-pointer">
                <i class="fa-solid fa-dumbbell text-brandBlue text-2xl"></i>
                <span class="text-xl font-bold tracking-wider">Gym Portal</span>
            </div>
           <nav class="mt-4 px-4 space-y-2">
                <a href="/owner-dashboard" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-chart-pie w-5"></i> <span>Dashboard</span>
                </a>
                <a href="/owner/members" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-users w-5"></i> <span>Members</span>
                </a>
                <a href="/owner/memberships" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-id-card w-5"></i> <span>Memberships</span>
                </a>
                <a href="/owner/attendance" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-calendar-check w-5"></i> <span>Attendance</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-comment-sms w-5"></i> <span>SMS Alerts</span>
                </a>
            </nav>
        </div>
        <div class="px-4 pb-6">
            <button onclick="logout()" class="w-full flex items-center gap-4 px-4 py-2 text-gray-400 hover:text-red-500 transition cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Log Out</span>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">Daily Attendance</h2>
            <div class="text-gray-400 font-medium" id="currentDateDisplay"></div>
        </header>

        <div class="p-8 space-y-6">
            
            <!-- Mark Attendance Box -->
            <div class="bg-panelBg rounded-2xl border border-gray-700 p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-clipboard-user text-brandBlue mr-2"></i> Mark Attendance</h3>
                <form id="markAttendanceForm" class="flex gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-wifi text-gray-500"></i>
                        </div>
                        <input type="text" id="member_identifier" placeholder="Scan RFID Card or Enter Member Email / Phone Number..." required autofocus
                            class="w-full bg-darkBg border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:outline-none focus:border-brandBlue focus:ring-1 focus:ring-brandBlue transition">
                    </div>
                    <button type="submit" class="bg-brandBlue hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-bold transition shadow-lg shadow-blue-500/20 flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> Mark In
                    </button>
                </form>
                <div id="attendanceMessage" class="mt-3 text-sm hidden"></div>
            </div>

            <!-- Today's Attendance Table -->
            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-white">Today's Log</h3>
                    <span class="bg-blue-500/10 text-brandBlue px-3 py-1 rounded-full text-xs font-bold" id="totalCount">0 Members</span>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-medium">Member Name</th>
                            <th class="px-6 py-4 font-medium">Time In</th>
                            <th class="px-6 py-4 font-medium">Method</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Loading today's attendance...</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <!-- JavaScript For API Integration -->
    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        // දවස පෙන්වීම
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDateDisplay').innerText = new Date().toLocaleDateString('en-US', options);

        document.addEventListener('DOMContentLoaded', fetchTodayAttendance);

        // අද පැමිණීම් ලබා ගැනීම
        function fetchTodayAttendance() {
            fetch('/api/attendance/today', { 
                headers: { 'Authorization': 'Bearer ' + ownerToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    renderTable(data.data);
                }
            })
            .catch(err => console.error(err));
        }

        function renderTable(logs) {
            let tbody = document.getElementById('attendance-table');
            document.getElementById('totalCount').innerText = logs.length + ' Members';

            if (!logs || logs.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No members arrived yet today.</td></tr>`;
                return;
            }

            let html = '';
            logs.forEach(log => {
                // Check-in සහ Check-out වෙලාවන් හදාගැනීම
                let timeIn = log.check_in_time ? new Date(log.check_in_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '--';
                let timeOut = log.check_out_time ? new Date(log.check_out_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '<span class="text-gray-500 text-xs italic">In Gym</span>';
                
                let methodBadge = log.method === 'RFID' 
                    ? `<span class="text-xs bg-purple-500/20 text-purple-400 px-2 py-1 rounded"><i class="fa-solid fa-wifi mr-1"></i> Scanner</span>`
                    : `<span class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded"><i class="fa-solid fa-keyboard mr-1"></i> Manual</span>`;

                html += `
                    <tr class="hover:bg-gray-800/50 transition animate-fade-in">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brandBlue/20 text-brandBlue flex items-center justify-center font-bold text-xs">${log.member_name.charAt(0)}</div>
                                <span class="font-semibold">${log.member_name}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-green-400 font-medium text-sm"><i class="fa-solid fa-arrow-right-to-bracket mr-1 text-xs"></i> ${timeIn}</div>
                            <div class="text-red-400 font-medium text-xs mt-1"><i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> ${timeOut}</div>
                        </td>
                        <td class="px-6 py-4">${methodBadge}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="deleteAttendance(${log.id})" class="text-red-400 hover:text-red-300 bg-red-400/10 px-2 py-1 rounded" title="Remove Entry"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // පැමිණීම සටහන් කිරීම
        document.getElementById('markAttendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const identifierInput = document.getElementById('member_identifier');
            const identifier = identifierInput.value;

            fetch('/api/attendance/mark', { 
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken
                },
                body: JSON.stringify({ identifier: identifier })
            })
            .then(res => res.json())
            .then(data => {
                const msgBox = document.getElementById('attendanceMessage');
                msgBox.classList.remove('hidden');
                
                if(data.status === 'success') {
                    msgBox.className = "mt-3 text-sm text-green-400 bg-green-400/10 p-3 rounded-lg border border-green-400/20";
                    msgBox.innerHTML = `<i class="fa-solid fa-circle-check mr-2"></i> ${data.message}`;
                    identifierInput.value = ''; // Input එක clear කරනවා
                    fetchTodayAttendance(); // Table එක update කරනවා
                } else {
                    msgBox.className = "mt-3 text-sm text-red-400 bg-red-400/10 p-3 rounded-lg border border-red-400/20";
                    msgBox.innerHTML = `<i class="fa-solid fa-circle-exclamation mr-2"></i> ${data.message || 'Member not found.'}`;
                }
                
                // තත්පර 3කින් මැසේජ් එක මකනවා
                setTimeout(() => { msgBox.classList.add('hidden'); }, 3000);
            })
            .catch(err => console.error(err));
        });

        // අත්වැරදීමකින් දැම්මොත් මකන්න
        function deleteAttendance(id) {
            if(!confirm('Remove this attendance entry?')) return;
            fetch('/api/attendance/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') fetchTodayAttendance();
            });
        }

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }
    </script>
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</body>
</html>