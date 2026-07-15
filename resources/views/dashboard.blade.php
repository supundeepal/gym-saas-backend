<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - Gym SaaS</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkBg: '#16171b',
                        panelBg: '#222327',
                        brandOrange: '#f55918'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <!-- ================= Sidebar ================= -->
    <aside class="w-64 bg-panelBg flex flex-col justify-between h-full border-r border-gray-800">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 cursor-pointer">
                <i class="fa-solid fa-server text-brandOrange text-2xl"></i>
                <span class="text-xl font-bold tracking-wider">Gym SaaS</span>
            </div>

            <nav class="mt-4 px-4 space-y-2">
                <a href="/" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-chart-line w-5"></i>
                    <span class="font-semibold">System Overview</span>
                </a>
                <a href="/manage-gyms" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-dumbbell w-5"></i>
                    <span>Manage Gyms</span>
                </a>
                <a href="/gym-owners" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-user-shield w-5"></i>
                    <span>Gym Owners</span>
                </a>
                <!-- අලුතින් දාපු Subscriptions ලින්ක් එක -->
                <a href="/subscriptions" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-tags w-5"></i>
                    <span>Subscriptions</span>
                </a>
            </nav>
        </div>

        <div class="px-4 pb-6">
            <button onclick="logout()" class="w-full flex items-center gap-4 px-4 py-2 text-gray-400 hover:text-red-500 transition cursor-pointer">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Log Out</span>
            </button>
        </div>
    </aside>

    <!-- ================= Main Content Area ================= -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        
        <!-- Topbar -->
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                <input type="text" placeholder="Search gyms, owners, or IDs..." class="w-full bg-panelBg text-gray-300 rounded-full py-2.5 pl-10 pr-4 focus:outline-none focus:ring-1 focus:ring-brandOrange text-sm border border-gray-700">
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-gray-700 border-2 border-brandOrange flex items-center justify-center text-white font-bold">S</div>
                    <div>
                        <h4 class="text-sm font-bold">Supun Deepal</h4>
                        <p class="text-xs text-brandOrange">System Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Widgets Area -->
        <div class="p-8 space-y-6">
            
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-2xl font-bold">System Overview</h2>
                
            </div>

            <!-- System Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-panelBg p-6 rounded-2xl border border-gray-700/50">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-gray-400 font-medium">Total Registered Gyms</h4>
                        <div class="bg-darkBg p-2 rounded-lg border border-gray-700 text-brandOrange">
                            <i class="fa-solid fa-dumbbell"></i>
                        </div>
                    </div>
                    <!-- මෙතන තමයි ඇත්ත Gyms ගාණ වැටෙන්නේ -->
                    <h2 id="stat-total-gyms" class="text-3xl font-bold mb-1">0</h2>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-gray-700/50">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-gray-400 font-medium">Active Gym Owners</h4>
                        <div class="bg-darkBg p-2 rounded-lg border border-gray-700 text-blue-400">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    </div>
                    <!-- මෙතන තමයි ඇත්ත Owners ගාණ වැටෙන්නේ -->
                    <h2 id="stat-active-owners" class="text-3xl font-bold mb-1">0</h2>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-gray-700/50">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-gray-400 font-medium">Total End Users</h4>
                        <div class="bg-darkBg p-2 rounded-lg border border-gray-700 text-purple-400">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <h2 id="stat-total-members" class="text-3xl font-bold mb-1">0</h2>
                </div>

                <div class="bg-panelBg p-6 rounded-2xl border border-gray-700/50">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-gray-400 font-medium">Monthly Revenue</h4>
                        <div class="bg-darkBg p-2 rounded-lg border border-gray-700 text-green-400">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </div>
                    </div>
                    <h2 id="stat-revenue" class="text-3xl font-bold mb-1">Rs. 0</h2>
                </div>
            </div>

            <!-- Recently Registered Gyms Table -->
            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Recently Registered Gyms</h3>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Gym Name</th>
                            <th class="px-6 py-4 font-medium">Joined Date</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                        </tr>
                    </thead>
                    <!-- මෙතනට තමයි Database එකෙන් එන ලිස්ට් එක වැටෙන්නේ -->
                    <tbody id="recent-gyms-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <!-- ================= Register Gym Modal ================= -->
    <div id="registerGymModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl relative">
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Register New Gym</h2>
                <button onclick="toggleModal('registerGymModal')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <form id="registerGymForm" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Gym Name</label>
                    <input type="text" id="gym_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Owner Name</label>
                    <input type="text" id="owner_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Owner Email</label>
                    <input type="email" id="owner_email" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Owner Password</label>
                    <input type="password" id="owner_password" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="toggleModal('registerGymModal')" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandOrange hover:bg-orange-600 text-white py-2 rounded-lg font-bold transition">Register Gym</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Scripts ================= -->
    <script>
        const superAdminToken = localStorage.getItem('gym_super_admin_token');
        if (!superAdminToken) {
            window.location.href = '/login';
        }

        // පිටුව ලෝඩ් වෙද්දීම ඇත්ත දත්ත ගන්නවා
        document.addEventListener('DOMContentLoaded', loadDashboardData);

        function loadDashboardData() {
            fetch('/api/dashboard-stats', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + superAdminToken 
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    // 1. කාඩ් වල ගණන් ටික දානවා
                    document.getElementById('stat-total-gyms').innerText = data.stats.total_gyms;
                    document.getElementById('stat-active-owners').innerText = data.stats.active_owners;
                    document.getElementById('stat-total-members').innerText = data.stats.total_members;
                    document.getElementById('stat-revenue').innerText = data.stats.revenue;

                    // 2. ජිම් ලිස්ට් එක ටේබල් එකට දානවා
                    let tableBody = '';
                    if(data.recent_gyms.length > 0) {
                        data.recent_gyms.forEach(gym => {
                            // දවස විතරක් වෙන් කරගැනීම
                            let dateJoined = new Date(gym.created_at).toLocaleDateString();
                            
                            tableBody += `
                                <tr class="hover:bg-gray-800/50 transition">
                                    <td class="px-6 py-4 font-semibold text-white">${gym.name}</td>
                                    <td class="px-6 py-4">${dateJoined}</td>
                                    <td class="px-6 py-4"><span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-xs font-bold">Active</span></td>
                                </tr>
                            `;
                        });
                    } else {
                        tableBody = `<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No gyms registered yet.</td></tr>`;
                    }
                    
                    document.getElementById('recent-gyms-table').innerHTML = tableBody;
                }
            })
            .catch(error => console.error('Error loading dashboard data:', error));
        }

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function logout() {
            localStorage.removeItem('gym_super_admin_token');
            window.location.href = '/login';
        }

        document.getElementById('registerGymForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            const data = {
                gym_name: document.getElementById('gym_name').value,
                owner_name: document.getElementById('owner_name').value,
                owner_email: document.getElementById('owner_email').value,
                owner_password: document.getElementById('owner_password').value
            };

            fetch('/api/register-gym', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + superAdminToken 
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if(result.status === 'success') {
                    alert('Gym successfully registered! 🎉');
                    toggleModal('registerGymModal');
                    document.getElementById('registerGymForm').reset(); 
                    
                    // අලුත් එකක් සේව් කළාම ඔටෝම ඩෑෂ්බෝඩ් එක අප්ඩේට් වෙන්න 
                    loadDashboardData(); 
                } else {
                    alert('Error: ' + (result.message || 'Something went wrong!'));
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>