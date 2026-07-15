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
                <a href="/manage-gyms" class="flex items-center gap-4 px-4 py-3 bg-brandOrange text-white shadow-lg rounded-lg transition">
                    <i class="fa-solid fa-dumbbell w-5"></i>
                    <span>Manage Gyms</span>
                </a>
                <a href="/gym-owners" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-user-shield w-5"></i>
                    <span>Gym Owners</span>
                </a>
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
                <button onclick="openAddGymModal()" class="bg-brandOrange hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-2 shadow-lg">
                    <i class="fa-solid fa-plus"></i> Register New Gym
                </button>
            </div>

            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400">
                        <tr>
                            <th class="px-6 py-4 font-medium">Gym ID</th>
                            <th class="px-6 py-4 font-medium">Gym Details</th>
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

    <!-- ================= Add Gym Modal (With Plan Selection) ================= -->
    <div id="addGymModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 overflow-y-auto pt-10 pb-10">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-4xl border border-gray-700 shadow-2xl relative my-auto">
            <div class="flex justify-between items-center mb-6 border-b border-gray-800 pb-4">
                <h2 class="text-2xl font-bold text-white"><i class="fa-solid fa-building text-brandOrange mr-2"></i> Register New Gym</h2>
                <button onclick="closeAddGymModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>
            
            <form id="addGymForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Gym Details Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-brandOrange border-b border-gray-700 pb-2 mb-4">1. Gym Details</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Gym Name *</label>
                            <input type="text" id="add_gym_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandOrange" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Address</label>
                            <input type="text" id="add_gym_address" placeholder="e.g. 123 Main St, Colombo" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandOrange">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Gym Phone Number</label>
                            <input type="text" id="add_gym_phone" placeholder="e.g. 0112345678" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandOrange">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Gym Logo (Optional)</label>
                            <input type="file" id="add_gym_logo" accept="image/jpeg, image/png, image/jpg" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600 cursor-pointer">
                        </div>
                    </div>

                    <!-- Owner Details Section -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-blue-400 border-b border-gray-700 pb-2 mb-4">2. Owner Details</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Owner Name *</label>
                            <input type="text" id="add_owner_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-400" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">NIC Number</label>
                            <input type="text" id="add_owner_nic" placeholder="e.g. 951234567V" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Owner Mobile Number</label>
                            <input type="text" id="add_owner_phone" placeholder="e.g. 0771234567" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-400">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Login Email *</label>
                                <input type="email" id="add_owner_email" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-400" required>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-1">Login Password *</label>
                                <input type="password" id="add_owner_password" minlength="6" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-400" required>
                            </div>
                        </div>
                    </div>

                    <!-- 🔴 අලුත් Subscription Plan Section එක 🔴 -->
                    <div class="space-y-4 md:col-span-2 border-t border-gray-800 pt-6">
                        <h3 class="text-lg font-bold text-green-400 border-b border-gray-700 pb-2 mb-4">3. Subscription Plan</h3>
                        <div class="w-full md:w-1/2">
                            <label class="block text-sm text-gray-400 mb-1">Select SaaS Plan *</label>
                            <select id="add_plan_id" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-green-400" required>
                                <option value="">Loading plans...</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="pt-6 mt-6 border-t border-gray-800 flex justify-end gap-4">
                    <button type="button" onclick="closeAddGymModal()" class="px-8 bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" id="submitAddGymBtn" class="px-8 bg-brandOrange hover:bg-orange-600 text-white py-3 rounded-lg font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> Register Gym
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Gym Modal -->
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

        document.addEventListener('DOMContentLoaded', () => {
            loadAllGyms();
            loadPlansForDropdown(); 
        });

        // Plans ටික අරන් Dropdown එකට දාන Function එක 
        function loadPlansForDropdown() {
            fetch('/api/plans', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                let select = document.getElementById('add_plan_id');
                if(data.status === 'success' && data.plans && data.plans.length > 0) {
                    let html = '<option value="">-- Select a Plan --</option>';
                    data.plans.forEach(plan => {
                        html += `<option value="${plan.id}">${plan.name} (Rs. ${parseFloat(plan.price).toLocaleString()}/mo)</option>`;
                    });
                    select.innerHTML = html;
                } else {
                    select.innerHTML = '<option value="">No plans available.</option>';
                }
            })
            .catch(err => console.error("Error loading plans:", err));
        }

        // Gyms ටික ලෝඩ් කරන Function එක (Error-proof කරා)
        // Gyms ටික ලෝඩ් කරන Function එක
        function loadAllGyms() {
            fetch('/api/all-gyms', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                let gymsArray = [];
                // Backend එකෙන් එන ඩේටා ෆෝමැට් එක කොහොම ආවත් වැඩ කරන විදිහට හැදුවා
                if (data.status === 'success' && data.gyms) {
                    gymsArray = data.gyms;
                } else if (Array.isArray(data)) {
                    gymsArray = data;
                }

                allGymsData = gymsArray;
                renderTable(allGymsData);
            })
            .catch(err => {
                console.error("Error fetching gyms:", err);
                document.getElementById('all-gyms-table').innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-red-500">Error loading gyms data.</td></tr>`;
            });
        }

        // ටේබල් එක Render කරන Function එක
        function renderTable(gyms) {
            let tableBody = '';
            if(gyms && gyms.length > 0) {
                gyms.forEach(gym => {
                    let gName = gym.gym_name || gym.name || 'Unknown Gym';
                    
                    let logoHtml = gym.logo_path ? 
                        `<img src="/storage/${gym.logo_path}" class="w-10 h-10 rounded-full object-cover border border-gray-600">` : 
                        `<div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center font-bold text-gray-400">${gName.charAt(0)}</div>`;
                    
                    let addressHtml = gym.address ? `<div class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-location-dot mr-1"></i> ${gym.address}</div>` : '';

                    // 🔴 Owner ගේ නම හරියටම ගන්න කෑල්ල (මේකෙයි අවුල තිබ්බේ) 🔴
                    let ownerName = 'N/A';
                    if(gym.users && gym.users.length > 0) {
                        ownerName = gym.users[0].name;
                    } else if(gym.owner_name) {
                        ownerName = gym.owner_name;
                    }

                    tableBody += `
                        <tr class="hover:bg-gray-800/50 transition border-b border-gray-800">
                            <td class="px-6 py-4 text-gray-500">#${gym.id}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    ${logoHtml}
                                    <div>
                                        <div class="font-bold text-white text-base">${gName}</div>
                                        ${addressHtml}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">${ownerName}</td>
                            <td class="px-6 py-4 font-bold text-green-400">Rs. ${gym.sms_balance || 0}</td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="openEditModal(${gym.id})" class="text-blue-400 hover:text-blue-300 bg-blue-400/10 px-3 py-1.5 rounded transition mx-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button onclick="deleteGym(${gym.id})" class="text-red-400 hover:text-red-300 bg-red-400/10 px-3 py-1.5 rounded transition mx-1"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No gyms found.</td></tr>`;
            }
            document.getElementById('all-gyms-table').innerHTML = tableBody;
        }

        // --- Add Gym (Register) Modal Functions ---
        function openAddGymModal() {
            document.getElementById('addGymModal').classList.remove('hidden');
            document.getElementById('addGymModal').classList.add('flex');
        }

        function closeAddGymModal() {
            document.getElementById('addGymModal').classList.add('hidden');
            document.getElementById('addGymModal').classList.remove('flex');
            document.getElementById('addGymForm').reset();
        }

        document.getElementById('addGymForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitAddGymBtn');
            submitBtn.innerHTML = "Registering...";
            submitBtn.disabled = true;

            let formData = new FormData();
            formData.append('gym_name', document.getElementById('add_gym_name').value);
            formData.append('gym_address', document.getElementById('add_gym_address').value);
            formData.append('gym_phone', document.getElementById('add_gym_phone').value);
            let logoFile = document.getElementById('add_gym_logo').files[0];
            if(logoFile) formData.append('gym_logo', logoFile);
            
            formData.append('owner_name', document.getElementById('add_owner_name').value);
            formData.append('owner_nic', document.getElementById('add_owner_nic').value);
            formData.append('owner_phone', document.getElementById('add_owner_phone').value);
            formData.append('owner_email', document.getElementById('add_owner_email').value);
            formData.append('owner_password', document.getElementById('add_owner_password').value);
            formData.append('plan_id', document.getElementById('add_plan_id').value);

            fetch('/api/register-gym', {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json', 
                    'Authorization': 'Bearer ' + superAdminToken 
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = `<i class="fa-solid fa-check"></i> Register Gym`;
                submitBtn.disabled = false;
                
                if(data.status === 'success') {
                    alert(data.message);
                    closeAddGymModal();
                    loadAllGyms(); 
                } else {
                    alert('Error: ' + (data.message || 'Validation Failed. Check if email already exists.'));
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.innerHTML = `<i class="fa-solid fa-check"></i> Register Gym`;
                submitBtn.disabled = false;
                alert("Something went wrong!");
            });
        });

        // --- Edit & Delete Modal Functions ---
        function openEditModal(gymId) {
            const gym = allGymsData.find(g => g.id === gymId);
            if(gym) {
                document.getElementById('edit_gym_id').value = gym.id;
                document.getElementById('edit_gym_name').value = gym.gym_name || gym.name;
                
                let ownerName = gym.users && gym.users.length > 0 ? gym.users[0].name : (gym.owner_name || '');
                document.getElementById('edit_owner_name').value = ownerName;
                
                document.getElementById('editGymModal').classList.remove('hidden');
                document.getElementById('editGymModal').classList.add('flex');
            }
        }

        function closeEditModal() {
            document.getElementById('editGymModal').classList.add('hidden');
            document.getElementById('editGymModal').classList.remove('flex');
        }

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
                    loadAllGyms();
                } else {
                    alert('Error updating gym.');
                }
            });
        });

        function deleteGym(gymId) {
            if(confirm('Are you sure you want to completely delete this Gym and its Owner account? This action cannot be undone!')) {
                fetch('/api/gyms/' + gymId, {
                    method: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + superAdminToken }
                })
                .then(res => res.json())
                .then(result => {
                    if(result.status === 'success') {
                        alert('Gym deleted successfully!');
                        loadAllGyms();
                    } else {
                        alert('Error deleting gym.');
                    }
                });
            }
        }

        document.getElementById('search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredGyms = allGymsData.filter(gym => {
                const gymName = (gym.gym_name || gym.name || '').toLowerCase();
                let ownerName = (gym.users && gym.users.length > 0) ? gym.users[0].name.toLowerCase() : (gym.owner_name || '').toLowerCase();
                
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