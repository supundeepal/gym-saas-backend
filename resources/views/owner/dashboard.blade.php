<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Owner Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#121417', panelBg: '#1c1f24', brandPrimary: '#3b82f6', brandSuccess: '#10b981' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-panelBg flex flex-col justify-between h-full border-r border-gray-800 flex-shrink-0">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 cursor-pointer">
                <i class="fa-solid fa-dumbbell text-brandPrimary text-2xl"></i>
                <span class="text-xl font-bold tracking-wider" id="sidebar-gym-name">Loading...</span>
            </div>
            <nav class="mt-4 px-4 space-y-2">
                <a href="/owner-dashboard" class="flex items-center gap-4 px-4 py-3 bg-brandPrimary text-white rounded-lg shadow-lg">
                    <i class="fa-solid fa-chart-pie w-5"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-users w-5"></i><span>Members</span></a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-id-card w-5"></i><span>Memberships</span></a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-calendar-check w-5"></i><span>Attendance</span></a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition"><i class="fa-solid fa-message w-5"></i><span>SMS & Alerts</span></a>
            </nav>
        </div>
        <div class="px-4 pb-6">
            <button onclick="logout()" class="w-full flex items-center gap-4 px-4 py-2 text-gray-400 hover:text-red-500 transition cursor-pointer"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Log Out</span></button>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-panelBg border-b border-gray-800">
            <h2 class="text-2xl font-bold">Welcome back, <span id="owner-name" class="text-brandPrimary">...</span>!</h2>
            <div class="flex items-center gap-6">
                <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-500"></div> System Active
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-panelBg p-6 rounded-2xl border border-gray-800 flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1">Total Members</p>
                        <h3 class="text-3xl font-bold text-white" id="stat-total-members">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500 text-xl"><i class="fa-solid fa-users"></i></div>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-gray-800 flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1">Active Memberships</p>
                        <h3 class="text-3xl font-bold text-white" id="stat-active-members">0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-500/10 flex items-center justify-center text-green-500 text-xl"><i class="fa-solid fa-heart-pulse"></i></div>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-gray-800 flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1">Monthly Revenue</p>
                        <h3 class="text-3xl font-bold text-white" id="stat-revenue">Rs. 0</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-500/10 flex items-center justify-center text-orange-500 text-xl"><i class="fa-solid fa-wallet"></i></div>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-blue-500/30 flex items-center justify-between relative overflow-hidden">
                    <div class="z-10">
                        <p class="text-blue-400 text-sm font-semibold mb-1">SMS Balance</p>
                        <h3 class="text-3xl font-bold text-white" id="stat-sms-balance">Rs. 0.00</h3>
                        <a href="#" class="text-xs text-blue-400 hover:text-blue-300 underline mt-2 block">Top-up Now</a>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 text-xl z-10"><i class="fa-solid fa-comment-sms"></i></div>
                </div>
            </div>

            <div class="bg-panelBg p-6 rounded-2xl border border-gray-800">
                <h3 class="text-lg font-bold mb-4">Recent Member Check-ins</h3>
                <p class="text-gray-500 text-sm">No recent check-ins found.</p>
            </div>
        </div>
    </main>

    <script>
        // 🔹 Gym Owner ගේ Token එක ගන්නවා (ලොගින් පිටුවෙන් මේ නමට සේව් කරන්න ඕන)
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/login';

        document.addEventListener('DOMContentLoaded', loadDashboardStats);

        function loadDashboardStats() {
            fetch('/api/owner/dashboard-stats', {
                headers: { 
                    'Accept': 'application/json', 
                    'Authorization': 'Bearer ' + ownerToken 
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // API එකෙන් ආපු ඇත්තම ඩේටා ටික පිටුවට දානවා
                    document.getElementById('sidebar-gym-name').innerText = data.gym_name;
                    document.getElementById('owner-name').innerText = data.owner_name;
                    document.getElementById('stat-sms-balance').innerText = 'Rs. ' + parseFloat(data.sms_balance).toFixed(2);
                    document.getElementById('stat-total-members').innerText = data.total_members;
                    document.getElementById('stat-active-members').innerText = data.active_members;
                    document.getElementById('stat-revenue').innerText = 'Rs. ' + data.monthly_revenue.toLocaleString();
                } else {
                    alert('Session expired. Please login again.');
                    logout();
                }
            })
            .catch(err => {
                console.error("Error loading stats:", err);
            });
        }

        function logout() {
            // Gym Owner ගේ Token එක මකලා දානවා
            localStorage.removeItem('gym_owner_token');
            
            // 👈 මෙන්න මේකයි වෙනස් වෙන්න ඕන (පරණ /login වෙනුවට /portal/login දාන්න)
            window.location.href = '/portal/login'; 
        }
    </script>
</body>
</html>