<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Memberships - Gym Owner</title>
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
                <a href="/owner/memberships" class="flex items-center gap-4 px-4 py-3 bg-brandBlue text-white rounded-lg shadow-lg transition">
                    <i class="fa-solid fa-id-card w-5"></i> <span>Memberships</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
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
            <h2 class="text-xl font-bold">Manage Memberships</h2>
            <button onclick="toggleModal('addPackageModal')" class="bg-brandBlue hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold transition flex items-center gap-2 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-plus"></i> Add New Package
            </button>
        </header>

        <div class="p-8 space-y-6">

<!-- Beautiful Package Cards Section -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-white mb-6"><i class="fa-solid fa-tags text-brandBlue mr-2"></i> Our Membership Plans</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- One Person Package Card -->
        <div class="bg-panelBg rounded-2xl border border-gray-700 shadow-xl overflow-hidden hover:border-brandBlue transition duration-300 transform hover:-translate-y-1">
            <div class="bg-gray-800/40 p-6 border-b border-gray-700 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user text-brandBlue text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">One Person Package</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">01 Month</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 3000.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">03 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 7000.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">06 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 16000.00</span>
                    </li>
                    <li class="flex justify-between items-center pt-1">
                        <span class="text-brandBlue font-bold">Annual</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 30000.00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Couple Package Card -->
        <div class="bg-panelBg rounded-2xl border border-brandBlue shadow-xl overflow-hidden relative transform hover:-translate-y-1 transition duration-300">
            <!-- Popular Badge -->
            <div class="absolute top-0 right-0 bg-brandBlue text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg uppercase tracking-wider">
                Most Popular
            </div>
            <div class="bg-gray-800/40 p-6 border-b border-gray-700 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-pink-500/20 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-user-group text-pink-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide">Couple Package</h3>
            </div>
            <div class="p-6">
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">01 Month</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 5000.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">03 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 13000.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">06 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 28000.00</span>
                    </li>
                    <li class="flex justify-between items-center pt-1">
                        <span class="text-brandBlue font-bold">Annual</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 50000.00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ladies & Kids Package Card -->
        <div class="bg-panelBg rounded-2xl border border-gray-700 shadow-xl overflow-hidden hover:border-brandBlue transition duration-300 transform hover:-translate-y-1">
            <div class="bg-gray-800/40 p-6 border-b border-gray-700 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-child-reaching text-purple-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white tracking-wide text-center">Ladies & Kids <br><span class="text-xs text-gray-400 font-normal">(Under 18)</span></h3>
            </div>
            <div class="p-6">
                <ul class="space-y-4 text-sm">
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">01 Month</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 2500.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">03 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 6500.00</span>
                    </li>
                    <li class="flex justify-between items-center border-b border-gray-700/50 pb-3">
                        <span class="text-gray-300 font-medium">06 Months</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 12000.00</span>
                    </li>
                    <li class="flex justify-between items-center pt-1">
                        <span class="text-brandBlue font-bold">Annual</span>
                        <span class="text-green-400 font-bold bg-green-400/10 px-3 py-1 rounded-full">Rs. 24000.00</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
<!-- End Beautiful Package Cards Section -->

            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-medium">Package Name</th>
                            <th class="px-6 py-4 font-medium">Duration (Days)</th>
                            <th class="px-6 py-4 font-medium">Price (Rs)</th>
                            <th class="px-6 py-4 font-medium">Description</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="packages-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading memberships...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Package Modal -->
    <div id="addPackageModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity">
        <div class="bg-panelBg w-full max-w-md rounded-2xl border border-gray-700 shadow-2xl p-6 relative">
            <button onclick="toggleModal('addPackageModal')" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-xl font-bold mb-6 text-white"><i class="fa-solid fa-id-card text-brandBlue mr-2"></i> Create Membership</h3>
            <form id="addPackageForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Package Name</label>
                    <input type="text" id="p_name" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Duration (In Days)</label>
                        <input type="number" id="p_duration" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Price (Rs.)</label>
                        <input type="number" step="0.01" id="p_price" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Description</label>
                    <textarea id="p_description" rows="2" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue"></textarea>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="toggleModal('addPackageModal')" class="flex-1 bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 py-2.5 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandBlue hover:bg-blue-600 text-white py-2.5 rounded-lg font-bold transition">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Package Modal -->
    <div id="editPackageModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity">
        <div class="bg-panelBg w-full max-w-md rounded-2xl border border-gray-700 shadow-2xl p-6 relative">
            <button onclick="toggleModal('editPackageModal')" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-xl font-bold mb-6 text-white"><i class="fa-solid fa-pen-to-square text-brandBlue mr-2"></i> Edit Membership</h3>
            <form id="editPackageForm" class="space-y-4">
                <input type="hidden" id="edit_package_id">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Package Name</label>
                    <input type="text" id="e_name" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Duration (In Days)</label>
                        <input type="number" id="e_duration" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 mb-1">Price (Rs.)</label>
                        <input type="number" step="0.01" id="e_price" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Description</label>
                    <textarea id="e_description" rows="2" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue"></textarea>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="toggleModal('editPackageModal')" class="flex-1 bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 py-2.5 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandBlue hover:bg-blue-600 text-white py-2.5 rounded-lg font-bold transition">Update Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        let allPackages = []; // පැකේජ් ටික තියාගන්න Array එකක්

        document.addEventListener('DOMContentLoaded', fetchPackages);

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // 1. Fetch Packages
        function fetchPackages() {
            fetch('/api/packages', { 
                headers: { 'Authorization': 'Bearer ' + ownerToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    allPackages = data.data; // ආපු ඩේටා ටික save කරගන්නවා
                    renderTable();
                }
            })
            .catch(err => console.error(err));
        }

        // 2. Render Table
        function renderTable() {
            let tbody = document.getElementById('packages-table');
            if (!allPackages || allPackages.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No memberships created yet.</td></tr>`;
                return;
            }

            let html = '';
            allPackages.forEach(p => {
                html += `
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="px-6 py-4 font-bold text-brandBlue">${p.name}</td>
                        <td class="px-6 py-4">${p.duration_in_days} Days</td>
                        <td class="px-6 py-4 text-green-400 font-bold">Rs. ${p.price}</td>
                        <td class="px-6 py-4 text-xs text-gray-400">${p.description || '-'}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openEditModal(${p.id})" class="text-blue-400 hover:text-blue-300 mr-3" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="deletePackage(${p.id})" class="text-red-400 hover:text-red-300" title="Delete"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // 3. Add Package
        document.getElementById('addPackageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('p_name').value,
                price: document.getElementById('p_price').value,
                duration_in_days: document.getElementById('p_duration').value,
                description: document.getElementById('p_description').value
            };

            fetch('/api/packages', { 
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' || data.status === 201) {
                    toggleModal('addPackageModal');
                    document.getElementById('addPackageForm').reset();
                    fetchPackages();
                } else { alert('Error: ' + (data.message || 'Validation failed.')); }
            })
            .catch(err => console.error(err));
        });

        // 4. Open Edit Modal
        function openEditModal(id) {
            const pkg = allPackages.find(p => p.id === id);
            if(!pkg) return;
            
            document.getElementById('edit_package_id').value = pkg.id;
            document.getElementById('e_name').value = pkg.name;
            document.getElementById('e_duration').value = pkg.duration_in_days;
            document.getElementById('e_price').value = pkg.price;
            document.getElementById('e_description').value = pkg.description || '';
            
            toggleModal('editPackageModal');
        }

        // 5. Submit Edit Form (Update)
        document.getElementById('editPackageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_package_id').value;
            const payload = {
                name: document.getElementById('e_name').value,
                price: document.getElementById('e_price').value,
                duration_in_days: document.getElementById('e_duration').value,
                description: document.getElementById('e_description').value
            };

            fetch('/api/packages/' + id, { 
                method: 'PUT',
                headers: { 
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    toggleModal('editPackageModal');
                    fetchPackages();
                } else { alert('Error: ' + data.message); }
            })
            .catch(err => console.error(err));
        });

        // 6. Delete Package
        function deletePackage(id) {
            if(!confirm('Are you sure you want to delete this package?')) return;

            fetch('/api/packages/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    fetchPackages();
                } else { alert('Error: ' + data.message); }
            })
            .catch(err => console.error(err));
        }

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }
    </script>
</body>
</html>