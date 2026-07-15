<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Alerts & Packages - Gym Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandBlue: '#3b82f6' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    @include('owner.sidebar')

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">SMS Alerts & Marketing</h2>
        </header>

        <div class="p-8 space-y-6">
            <!-- SMS Balance Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 shadow-lg flex justify-between items-center relative overflow-hidden">
                <i class="fa-solid fa-comment-sms text-9xl absolute -right-6 -bottom-6 text-white/10"></i>
                <div>
                    <h3 class="text-blue-100 font-medium mb-1">Available SMS Balance</h3>
                    <h2 class="text-5xl font-bold text-white" id="smsBalance">Loading...</h2>
                </div>
                <div class="bg-white/20 p-4 rounded-full">
                    <i class="fa-solid fa-coins text-2xl text-white"></i>
                </div>
            </div>

            <!-- Send SMS Form -->
            <div class="bg-panelBg rounded-2xl border border-gray-700 p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-paper-plane text-brandBlue mr-2"></i> Send Message</h3>
                <form id="sendSmsForm" class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Send To</label>
                        <select id="sms_target" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                            <option value="all">All Active Members</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Message Body <span class="text-xs text-gray-500 float-right" id="charCount">0/160 characters (1 SMS)</span></label>
                        <textarea id="sms_message" rows="3" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-brandBlue hover:bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold transition shadow-lg shadow-blue-500/20">
                            Send SMS <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <hr class="border-gray-800 my-6">

            <!-- Buy SMS Packages Section -->
            <div>
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-store text-brandBlue mr-2"></i> Buy SMS Packages</h3>
                <p class="text-gray-400 text-sm mb-6">Select a package below, transfer the amount to our bank account, and upload the payment slip to top up your balance.</p>
                <div id="packages-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <p class="text-gray-500">Loading packages...</p>
                </div>
            </div>

            <!-- 🔴 අලුත් Purchase History කෑල්ල 🔴 -->
            <div class="border-t border-gray-800 pt-8 mt-8">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-clock-rotate-left text-brandBlue mr-2"></i> My Purchase History</h3>
                <div class="bg-panelBg rounded-2xl border border-gray-700 overflow-hidden shadow-lg">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Package</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody id="history-table" class="divide-y divide-gray-800 text-gray-300">
                            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">Loading history...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- ================= Payment Slip Upload Modal ================= -->
    <div id="paymentModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Upload Payment Slip</h2>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="mb-4 bg-blue-900/20 border border-blue-800 p-4 rounded-lg">
                <p class="text-sm text-blue-200">You are requesting the <strong id="modal_pkg_name" class="text-white"></strong>.</p>
                <p class="text-sm text-blue-200 mt-1">Please upload a clear image of your bank transfer slip or receipt.</p>
            </div>

            <form id="paymentForm" class="space-y-4">
                <input type="hidden" id="selected_package_id">
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Select Slip Image (JPG, PNG)</label>
                    <input type="file" id="slip_file" accept="image/jpeg, image/png, image/jpg" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandBlue file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closePaymentModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" id="submitBtn" class="flex-1 bg-brandBlue hover:bg-blue-600 text-white py-2 rounded-lg font-bold transition">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        // 🔴 මෙතන තමයි පිටුව ලෝඩ් වෙද්දි ඔක්කොම කෝල් වෙන්නේ 🔴
        document.addEventListener('DOMContentLoaded', () => {
            fetchSmsBalance();
            fetchSmsPackages();
            fetchMyPurchases(); // 👈 අලුත් History එක මෙතනින් කෝල් වෙනවා
        });

        document.getElementById('sms_message').addEventListener('input', function() {
            let length = this.value.length;
            let smsCount = Math.ceil(length / 160) || 1;
            document.getElementById('charCount').innerText = `${length}/160 characters (${smsCount} SMS)`;
        });

        // 1. Balance එක ගැනීම
        function fetchSmsBalance() {
            fetch('/api/owner/sms-balance', { 
                headers: { 'Authorization': 'Bearer ' + ownerToken, 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('smsBalance').innerText = data.balance;
                } else {
                    document.getElementById('smsBalance').innerText = "0";
                }
            });
        }

        // 2. Super Admin හදපු Packages ටික පෙන්වීම
        function fetchSmsPackages() {
            fetch('/api/sms-packages', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                let container = document.getElementById('packages-container');
                if(data.status === 'success' && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(pkg => {
                        html += `
                            <div class="bg-panelBg p-6 rounded-2xl border border-gray-700 hover:border-brandBlue transition flex flex-col justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2">${pkg.name}</h4>
                                    <h2 class="text-3xl font-bold text-brandBlue mb-1">Rs. ${parseFloat(pkg.price).toLocaleString()}</h2>
                                    <p class="text-gray-400 mb-6 font-semibold">${pkg.sms_count} SMS Credits</p>
                                </div>
                                <button onclick="openPaymentModal(${pkg.id}, '${pkg.name}')" class="w-full bg-gray-700 hover:bg-brandBlue text-white py-2 rounded-lg font-bold transition">
                                    Request to Buy
                                </button>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-gray-500">No SMS packages available.</p>';
                }
            });
        }

        // Modal Open / Close
        function openPaymentModal(packageId, packageName) {
            document.getElementById('selected_package_id').value = packageId;
            document.getElementById('modal_pkg_name').innerText = packageName;
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
            document.getElementById('paymentForm').reset();
        }

        // 3. Package එකක් මිලදී ගැනීමට Slip එක අප්ලෝඩ් කිරීම
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerText = "Uploading...";
            submitBtn.disabled = true;

            let formData = new FormData();
            formData.append('package_id', document.getElementById('selected_package_id').value);
            formData.append('slip', document.getElementById('slip_file').files[0]);

            fetch('/api/owner/sms/buy', {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json', 
                    'Authorization': 'Bearer ' + ownerToken 
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerText = "Submit Request";
                submitBtn.disabled = false;
                
                if(data.status === 'success') {
                    alert(data.message);
                    closePaymentModal();
                    fetchMyPurchases(); // 👈 අප්ලෝඩ් කරපු ගමන් History ටේබල් එක අලුත් වෙනවා
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.innerText = "Submit Request";
                submitBtn.disabled = false;
                alert("An error occurred while uploading the slip.");
            });
        });

        // 🔴 4. Purchase History එක ගන්න අලුත් Function එක 🔴
        function fetchMyPurchases() {
            fetch('/api/owner/sms-purchases', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('history-table');
                if(data.status === 'success' && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(req => {
                        let date = new Date(req.created_at).toLocaleDateString();
                        let statusBadge = '';
                        if(req.status === 'pending') statusBadge = `<span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold">Pending Approval</span>`;
                        else if(req.status === 'approved') statusBadge = `<span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-xs font-bold">Active</span>`;
                        else statusBadge = `<span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-bold">Rejected</span>`;

                        html += `
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-400">${date}</td>
                                <td class="px-6 py-4 font-bold text-white">${req.package ? req.package.name : 'Unknown Package'}</td>
                                <td class="px-6 py-4">${statusBadge}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No purchase history found.</td></tr>`;
                }
            });
        }

        // 5. SMS යැවීම
        document.getElementById('sendSmsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if(!confirm('Send this message? This will deduct from your balance.')) return;

            const payload = {
                target: document.getElementById('sms_target').value,
                message: document.getElementById('sms_message').value
            };

            fetch('/api/owner/sms/send', { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Message sent successfully!');
                    document.getElementById('sendSmsForm').reset();
                    document.getElementById('charCount').innerText = "0/160 characters (1 SMS)";
                    fetchSmsBalance(); 
                } else {
                    alert('Error: ' + data.message);
                }
            });
        });

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }
    </script>
</body>
</html>