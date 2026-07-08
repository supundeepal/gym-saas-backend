<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gyms - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandOrange: '#f55918' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

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

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <div class="relative w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Search gyms by name or owner..." class="w-full bg-panelBg text-gray-300 rounded-full py-2.5 pl-10 pr-4 focus:outline-none focus:ring-1 focus:ring-brandOrange text-sm border border-gray-700">
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

        <div class="p-8 space-y-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Manage Gyms</h2>
            </div>

            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Gym ID</th>
                            <th class="px-6 py-4 font-medium">Gym Name</th>
                            <th class="px-6 py-4 font-medium">Owner Name</th>
                            <th class="px-6 py-4 font-medium">SMS Balance</th> 
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="all-gyms-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading gyms data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="editGymModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Edit Gym Details</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="editGymForm" class="space-y-4">
                <input type="hidden" id="edit_gym_id">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Gym Name</label>
                    <input type="text" id="edit_gym_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Owner Name</label>
                    <input type="text" id="edit_owner_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandOrange hover:bg-orange-600 text-white py-2 rounded-lg font-bold transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const superAdminToken = localStorage.getItem('gym_super_admin_token');
        if (!superAdminToken) window.location.href = '/login';

        let allGymsData = []; 

        document.addEventListener('DOMContentLoaded', loadAllGyms);

        function loadAllGyms() {
            fetch('/api/all-gyms', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    allGymsData = data.gyms;
                    renderTable(allGymsData);
                }
            });
        }

        function renderTable(gyms) {
            let tableBody = '';
            if(gyms.length > 0) {
                gyms.forEach(gym => {
                    tableBody += `
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500">#${gym.id}</td>
                            <td class="px-6 py-4 font-semibold text-white">${gym.gym_name}</td>
                            <td class="px-6 py-4">${gym.owner_name || 'N/A'}</td>
                            
                            <td class="px-6 py-4 font-bold text-green-400">Rs. ${gym.sms_balance}</td>
                            
                            <td class="px-6 py-4 text-right">
                                <button onclick="openEditModal(${gym.id})" class="text-blue-400 hover:text-blue-300 mx-2"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button onclick="deleteGym(${gym.id})" class="text-red-400 hover:text-red-300 mx-2"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No gyms found.</td></tr>`;
            }
            document.getElementById('all-gyms-table').innerHTML = tableBody;
        }

        // --- Edit Modal Functions ---
        function openEditModal(gymId) {
            // අදාළ ජිම් එකේ දත්ත හොයාගන්නවා
            const gym = allGymsData.find(g => g.id === gymId);
            if(gym) {
                document.getElementById('edit_gym_id').value = gym.id;
                document.getElementById('edit_gym_name').value = gym.gym_name;
                document.getElementById('edit_owner_name').value = gym.owner_name;
                
                // Modal එක පෙන්වනවා
                document.getElementById('editGymModal').classList.remove('hidden');
                document.getElementById('editGymModal').classList.add('flex');
            }
        }

        function closeEditModal() {
            document.getElementById('editGymModal').classList.add('hidden');
            document.getElementById('editGymModal').classList.remove('flex');
        }

        // --- Submit Edit Form ---
        document.getElementById('editGymForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const gymId = document.getElementById('edit_gym_id').value;
            const data = {
                gym_name: document.getElementById('edit_gym_name').value,
                owner_name: document.getElementById('edit_owner_name').value
            };

            fetch('/api/gyms/' + gymId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + superAdminToken
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if(result.status === 'success') {
                    alert('Gym updated successfully!');
                    closeEditModal();
                    loadAllGyms(); // ටේබල් එක අලුත් කරනවා
                } else {
                    alert('Error updating gym.');
                }
            });
        });

        // --- Delete Gym Function ---
        function deleteGym(gymId) {
            // මකන්න කලින් අහනවා 
            if(confirm('Are you sure you want to completely delete this Gym and its Owner account? This action cannot be undone!')) {
                fetch('/api/gyms/' + gymId, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + superAdminToken }
                })
                .then(res => res.json())
                .then(result => {
                    if(result.status === 'success') {
                        alert('Gym deleted successfully!');
                        loadAllGyms(); // ටේබල් එක අලුත් කරනවා
                    } else {
                        alert('Error deleting gym.');
                    }
                });
            }
        }

        // Search Function
        document.getElementById('search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredGyms = allGymsData.filter(gym => {
                const gymName = (gym.gym_name || '').toLowerCase();
                const ownerName = (gym.owner_name || '').toLowerCase();
                return gymName.includes(searchTerm) || ownerName.includes(searchTerm);
            });
            renderTable(filteredGyms);
        });

        function logout() {
            localStorage.removeItem('gym_super_admin_token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>