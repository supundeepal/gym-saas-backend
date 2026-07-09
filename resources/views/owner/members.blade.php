<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - Gym Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandBlue: '#3b82f6' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

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
                <a href="/owner/members" class="flex items-center gap-4 px-4 py-3 bg-brandBlue text-white rounded-lg shadow-lg transition">
                    <i class="fa-solid fa-users w-5"></i> <span>Members</span>
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">
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

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">Manage Members</h2>
            <button onclick="toggleModal('addMemberModal')" class="bg-brandBlue hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold transition flex items-center gap-2 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-plus"></i> Add New Member
            </button>
        </header>

        <div class="p-8 space-y-6">
            <div class="bg-panelBg rounded-2xl border border-gray-700/50 overflow-hidden shadow-lg">
                <table class="w-full text-left text-sm">
                    <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-medium">Name</th>
                            <th class="px-6 py-4 font-medium">Contact Info</th>
                            <th class="px-6 py-4 font-medium">RFID Tag</th>
                            <th class="px-6 py-4 font-medium">Joined Date</th>
                            <th class="px-6 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="members-table" class="divide-y divide-gray-800 text-gray-300">
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading members...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="addMemberModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity">
        <div class="bg-panelBg w-full max-w-md rounded-2xl border border-gray-700 shadow-2xl p-6 relative">
            <button onclick="toggleModal('addMemberModal')" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
            <h3 class="text-xl font-bold mb-6 text-white"><i class="fa-solid fa-user-plus text-brandBlue mr-2"></i> Register Member</h3>
            
            <form id="addMemberForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Full Name</label>
                    <input type="text" id="m_name" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Email Address</label>
                    <input type="email" id="m_email" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Phone Number</label>
                    <input type="text" id="m_phone" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">Temporary Password</label>
                    <input type="password" id="m_password" required minlength="6" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" onclick="toggleModal('addMemberModal')" class="flex-1 bg-transparent border border-gray-600 text-gray-300 hover:bg-gray-800 py-2.5 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandBlue hover:bg-blue-600 text-white py-2.5 rounded-lg font-bold transition">Save Member</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        document.addEventListener('DOMContentLoaded', fetchMembers);

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        // සාමාජිකයින්ගේ දත්ත Backend එකෙන් ගැනීම
        function fetchMembers() {
            fetch('/api/owner/members', {
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

        // Table එකට දත්ත පිරවීම
        function renderTable(members) {
            let tbody = document.getElementById('members-table');
            if (members.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No members registered yet.</td></tr>`;
                return;
            }

            let html = '';
            members.forEach(m => {
                let rfidBadge = m.rfid_number ? `<span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-1 rounded text-xs"><i class="fa-solid fa-wifi mr-1"></i> ${m.rfid_number}</span>` : `<span class="text-gray-500 text-xs italic">Not Assigned</span>`;
                let joined = new Date(m.created_at).toLocaleDateString();

                html += `
                    <tr class="hover:bg-gray-800/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brandBlue/20 text-brandBlue flex items-center justify-center font-bold text-xs">${m.name.charAt(0)}</div>
                                <span class="font-semibold">${m.name}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-300"><i class="fa-solid fa-envelope mr-1 text-gray-500"></i> ${m.email}</div>
                            <div class="text-xs text-gray-400 mt-1"><i class="fa-solid fa-phone mr-1 text-gray-500"></i> ${m.phone || 'N/A'}</div>
                        </td>
                        <td class="px-6 py-4">${rfidBadge}</td>
                        <td class="px-6 py-4 text-xs text-gray-400">${joined}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-gray-400 hover:text-brandBlue transition px-2" title="Assign RFID"><i class="fa-solid fa-id-badge"></i></button>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        // අලුත් Member කෙනෙක් Save කිරීම
        document.getElementById('addMemberForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const payload = {
                name: document.getElementById('m_name').value,
                email: document.getElementById('m_email').value,
                phone: document.getElementById('m_phone').value,
                password: document.getElementById('m_password').value
            };

            fetch('/api/owner/members', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + ownerToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Member added successfully!');
                    toggleModal('addMemberModal');
                    document.getElementById('addMemberForm').reset();
                    fetchMembers(); // අලුත් කෙනාවත් එක්කම Table එක Update කරනවා
                } else {
                    alert('Error: ' + (data.message || 'Validation failed. Check email.'));
                }
            })
            .catch(err => console.error(err));
        });

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }
    </script>
</body>
</html>