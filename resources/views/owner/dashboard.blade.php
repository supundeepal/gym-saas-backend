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

@include('owner.sidebar')


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
        // Get gym owner token saved in localStorage
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) {
            window.location.href = '/portal/login';
        }

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }

        // Load dashboard stats from API
        (function loadStats(){
            fetch('/api/owner/dashboard', {
                headers: { 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data && data.status === 'success') {
                    document.getElementById('sidebar-gym-name').innerText = data.gym_name || '';
                    document.getElementById('owner-name').innerText = data.owner_name || '';
                    document.getElementById('stat-sms-balance').innerText = 'Rs. ' + (parseFloat(data.sms_balance) || 0).toFixed(2);
                    document.getElementById('stat-total-members').innerText = data.total_members || 0;
                    document.getElementById('stat-active-members').innerText = data.active_members || 0;
                    document.getElementById('stat-revenue').innerText = 'Rs. ' + ((data.monthly_revenue && Number(data.monthly_revenue)) ? Number(data.monthly_revenue).toLocaleString() : '0');
                } else {
                    alert('Session expired. Please login again.');
                    logout();
                }
            })
            .catch(err => console.error('Error loading stats:', err));
        })();
    </script>
</body>
</html>