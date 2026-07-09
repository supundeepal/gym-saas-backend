<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Owners - Super Admin</title>
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
                <a href="/gym-owners" class="flex items-center gap-4 px-4 py-3 bg-brandOrange text-white rounded-lg shadow-lg transition">
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
                <input type="text" id="search-input" placeholder="Search owners by name or email..." class="w-full bg-panelBg text-gray-300 rounded-full py-2.5 pl-10 pr-4 focus:outline-none focus:ring-1 focus:ring-brandOrange text-sm border border-gray-700">
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
                <h2 class="text-2xl font-bold">Gym Owners Directory</h2>
            </div>

            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-medium">Owner ID</th>
                            <th class="px-6 py-4 font-medium">Owner Name</th>
                            <th class="px-6 py-4 font-medium">Email Address</th>
                            <th class="px-6 py-4 font-medium">Associated Gym</th>
                            <th class="px-6 py-4 font-medium">Account Created</th>
                            <!-- 🔹 අලුතින් එකතු කළ තීරුව (Actions) -->
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="owners-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr>
                            <!-- 🔹 Loading පෙන්නද්දි colspan එක 6 ක් කළා -->
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Loading owners data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        const superAdminToken = localStorage.getItem('gym_super_admin_token');
        if (!superAdminToken) window.location.href = '/admin/login';

        let allOwnersData = [];

        document.addEventListener('DOMContentLoaded', loadAllOwners);

        function loadAllOwners() {
            fetch('/api/gym-owners', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    allOwnersData = data.owners;
                    renderTable(allOwnersData);
                }
            })
            .catch(err => console.error(err));
        }

        function renderTable(owners) {
            let tableBody = '';
            if(owners.length > 0) {
                owners.forEach(owner => {
                    let dateJoined = new Date(owner.created_at).toLocaleDateString();
                    tableBody += `
                        <tr class="hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-gray-500">#${owner.id}</td>
                            <td class="px-6 py-4 font-semibold text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-brandOrange">${owner.owner_name ? owner.owner_name.charAt(0) : 'U'}</div>
                                    ${owner.owner_name}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-blue-400">${owner.email}</td>
                            <td class="px-6 py-4"><span class="bg-gray-700 px-3 py-1 rounded-full text-xs">${owner.gym_name || 'No Gym'}</span></td>
                            <td class="px-6 py-4">${dateJoined}</td>
                            
                            <!-- 🔹 Login As බොත්තම දැම්මේ මෙතනටයි -->
                            <td class="px-6 py-4 text-right">
                                <button onclick="loginAsOwner(${owner.id})" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition inline-flex items-center gap-2">
                                    <i class="fa-solid fa-right-to-bracket"></i> Login As
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tableBody = `<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No Gym Owners found.</td></tr>`;
            }
            document.getElementById('owners-table').innerHTML = tableBody;
        }

        document.getElementById('search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredOwners = allOwnersData.filter(owner => {
                return (owner.owner_name || '').toLowerCase().includes(searchTerm) || 
                       (owner.email || '').toLowerCase().includes(searchTerm);
            });
            renderTable(filteredOwners);
        });

        // 🔹 අලුත් Login As (Impersonate) ෆන්ක්ෂන් එක
        function loginAsOwner(ownerId) {
            fetch(`/api/admin/impersonate/${ownerId}`, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + superAdminToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // අදාළ Owner ගේ Token එක බ්‍රවුසරයට දාලා අලුත් ටැබ් එකකට යවනවා
                    localStorage.setItem('gym_owner_token', data.token);
                    window.open('/owner-dashboard', '_blank');
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => console.error("Impersonate error: ", err));
        }

        function logout() {
            localStorage.removeItem('gym_super_admin_token');
            // 🔹 Logout එකත් අලුත් admin/login එකට යැව්වා
            window.location.href = '/admin/login';
        }
    </script>
</body>
</html>