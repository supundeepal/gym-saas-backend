<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions & Packages - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandOrange: '#f55918' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <!-- Super Admin ගේ මෙනු එක -->
    @include('admin-sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">SMS Packages Management</h2>
            <button onclick="toggleModal('addPackageModal')" class="bg-brandOrange hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg font-bold transition flex items-center gap-2 shadow-lg">
                <i class="fa-solid fa-plus"></i> Add SMS Package
            </button>
        </header>

        <div class="p-8 space-y-6">
            <!-- Packages Table -->
            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Available SMS Packages</h3>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-medium">Package Name</th>
                            <th class="px-6 py-4 font-medium">SMS Count</th>
                            <th class="px-6 py-4 font-medium">Price (Rs.)</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="packages-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Loading packages...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Package Modal -->
    <div id="addPackageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Create SMS Package</h2>
                <button onclick="toggleModal('addPackageModal')" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            <form id="addPackageForm" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Package Name (e.g. Bronze Pack)</label>
                    <input type="text" id="p_name" placeholder="Bronze Pack" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Total SMS Count</label>
                    <input type="number" id="p_count" placeholder="1000" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Price (Rs.)</label>
                    <input type="number" id="p_price" step="0.01" placeholder="500.00" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandOrange" required>
                </div>
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="toggleModal('addPackageModal')" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandOrange hover:bg-orange-600 text-white py-2 rounded-lg font-bold transition">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript For API Integration -->
    <script>
        const superAdminToken = localStorage.getItem('gym_super_admin_token');
        if (!superAdminToken) window.location.href = '/login';

        document.addEventListener('DOMContentLoaded', fetchPackages);

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

        // Backend එකෙන් පැකේජ් ටික ගන්නවා
        function fetchPackages() {
            fetch('/api/sms-packages', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    renderTable(data.data);
                }
            })
            .catch(err => console.error(err));
        }

        // ටේබල් එකට පැකේජ් ටික දානවා
        function renderTable(packages) {
            let tbody = document.getElementById('packages-table');
            if (packages.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No packages created yet.</td></tr>`;
                return;
            }

            let html = '';
            packages.forEach(p => {
                html += `
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="px-6 py-4 font-semibold text-white"><i class="fa-solid fa-box text-brandOrange mr-2"></i> ${p.name}</td>
                        <td class="px-6 py-4 text-blue-400 font-bold">${p.sms_count} SMS</td>
                        <td class="px-6 py-4 font-medium">Rs. ${parseFloat(p.price).toFixed(2)}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="deletePackage(${p.id})" class="text-red-400 hover:text-red-300 bg-red-400/10 px-3 py-1.5 rounded transition"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // අලුත් පැකේජ් එකක් සේව් කරනවා
        document.getElementById('addPackageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const payload = {
                name: document.getElementById('p_name').value,
                sms_count: document.getElementById('p_count').value,
                price: document.getElementById('p_price').value
            };

            fetch('/api/admin/sms-packages', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken 
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    toggleModal('addPackageModal');
                    document.getElementById('addPackageForm').reset();
                    fetchPackages(); // අලුත් පැකේජ් එකත් එක්කම ටේබල් එක අප්ඩේට් කරනවා
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                }
            })
            .catch(err => console.error(err));
        });

        // පැකේජ් එකක් මකනවා
        function deletePackage(id) {
            if(!confirm('Are you sure you want to delete this package?')) return;
            
            fetch('/api/admin/sms-packages/' + id, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    fetchPackages();
                }
            })
            .catch(err => console.error(err));
        }

        function logout() {
            localStorage.removeItem('gym_super_admin_token');
            window.location.href = '/login';
        }
    </script>
</body>
</html>