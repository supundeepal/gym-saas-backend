<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subscriptions - Super Admin</title>
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

    <!-- Sidebar -->
    @include('admin-sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <!-- Header with Notifications (Super Admin) -->
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800 relative z-40">
            <h2 class="text-xl font-bold">Plans & SMS Packages</h2>
            
            <div class="flex items-center gap-6">
              <!-- 🔴 Notification Bell 🔴 -->
               <!-- 🔴 Notification Bell (අලුත් කරපු එක) 🔴 -->
                <div class="relative cursor-pointer flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 transition shadow-lg border border-gray-700" onclick="toggleNotifications()">
                    <i class="fa-solid fa-bell text-gray-300"></i>
                    <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
                    
                    <!-- Notification Dropdown -->
                    <div id="notif-dropdown" class="absolute right-0 top-12 w-80 bg-panelBg border border-gray-700 rounded-xl shadow-2xl hidden z-50 overflow-hidden text-left cursor-default">
                        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-700 bg-darkBg">
                            <h4 class="font-bold text-white">Notifications</h4>
                            <button onclick="markAsRead(event)" class="text-xs text-blue-400 hover:underline">Mark all read</button>
                        </div>
                        <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-800">
                            <!-- මැසේජ් ටික මෙතනට එනවා -->
                        </div>
                    </div>
                </div>

                <!-- Profile Info (Supun Deepal) -->
                <div class="flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-gray-700 border-2 border-brandOrange flex items-center justify-center text-white font-bold">S</div>
                    <div class="text-left">
                        <h4 class="text-sm font-bold">Supun Deepal</h4>
                        <p class="text-xs text-brandOrange">System Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8">
            
            <!-- SaaS Subscription Plans Section -->
            <div>
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-cube text-brandOrange"></i> SaaS Subscription Plans
                    </h3>
                    
                    <div class="flex items-center gap-3 bg-panelBg px-4 py-2 rounded-full border border-gray-700">
                        <span class="text-sm font-semibold text-gray-400" id="text-monthly">Monthly</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="billingToggle" class="sr-only peer" onchange="toggleBilling()">
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brandOrange"></div>
                        </label>
                        <span class="text-sm font-bold text-white flex items-center gap-2" id="text-annually">
                            Annually <span class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded text-[10px] uppercase tracking-wider">Save 15%</span>
                        </span>
                    </div>

                    <button onclick="openAddPlanModal()" class="bg-brandOrange hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-lg">
                        <i class="fa-solid fa-plus mr-1"></i> Add New Plan
                    </button>
                </div>
                
                <div id="plans-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <p class="text-gray-500">Loading plans...</p>
                </div>
            </div>

            <hr class="border-gray-800">

            <!-- Bulk SMS Packages Section -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold flex items-center gap-2">
                        <i class="fa-solid fa-message text-blue-500"></i> Bulk SMS Packages
                    </h3>
                    <button onclick="openAddSmsModal()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add SMS Package
                    </button>
                </div>
                <div id="sms-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <p class="text-gray-500">Loading SMS Packages...</p>
                </div>
            </div>

            <!-- SMS Requests Table -->
            <div class="border-t border-gray-800 pt-8 mt-8">
                <h3 class="text-2xl font-bold flex items-center gap-2 mb-6"><i class="fa-solid fa-file-invoice text-yellow-500"></i> SMS Purchase Requests</h3>
                <div class="bg-panelBg rounded-2xl border border-gray-700 overflow-hidden shadow-lg">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4">Gym Name</th>
                                <th class="px-6 py-4">Package Requested</th>
                                <th class="px-6 py-4">Payment Slip</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requests-table" class="divide-y divide-gray-800 text-gray-300">
                            <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">Loading requests...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🔴 SaaS Subscription Approvals Section (දැන් හරියටම Main එක ඇතුලේ තියෙනවා) 🔴 -->
            <div class="border-t border-gray-800 pt-8 mt-8">
                <h3 class="text-2xl font-bold flex items-center gap-2 mb-4"><i class="fa-solid fa-file-invoice-dollar text-brandOrange"></i> SaaS Subscription Requests</h3>
                
                <div class="bg-panelBg rounded-2xl border border-gray-700 overflow-hidden shadow-lg">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Gym Name</th>
                                <th class="px-6 py-4">Requested Plan</th>
                                <th class="px-6 py-4">Slip</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="saas-requests-table" class="divide-y divide-gray-800 text-gray-300">
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Loading requests...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- ================= Add Plan Modal ================= -->
    <div id="addPlanModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl">
            <h2 class="text-xl font-bold mb-6">Create New Plan</h2>
            <form id="addPlanForm" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Plan Name</label>
                    <input type="text" id="plan_name" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Monthly Price (Rs.)</label>
                    <input type="number" id="plan_price" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Max Members (0 for Unlimited)</label>
                    <input type="number" id="plan_members" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Features (Comma separated)</label>
                    <input type="text" id="plan_features" placeholder="Core Gym, Basic Reports, Alerts" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeAddPlanModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold">Cancel</button>
                    <button type="submit" class="flex-1 bg-brandOrange hover:bg-orange-600 text-white py-2 rounded-lg font-bold">Save Plan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= Add SMS Package Modal ================= -->
    <div id="addSmsModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl">
            <h2 class="text-xl font-bold mb-6">Create SMS Package</h2>
            <form id="addSmsForm" class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Package Name</label>
                    <input type="text" id="sms_name" placeholder="e.g., 1000 SMS" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">SMS Count</label>
                    <input type="number" id="sms_count" placeholder="1000" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Price (Rs.)</label>
                    <input type="number" id="sms_price" placeholder="1000" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white" required>
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeAddSmsModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-500 text-white py-2 rounded-lg font-bold">Save Package</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const superAdminToken = localStorage.getItem('gym_super_admin_token');
        if (!superAdminToken) window.location.href = '/login';

        let allPlansData = [];
        let isAnnually = false;

       document.addEventListener('DOMContentLoaded', () => {
        loadPlans();
        loadSmsPackages(); 
        loadSmsRequests(); 
        loadSaaSRequests(); 
        
        loadNotifications(); // 🔴 මෙන්න මේක අලුතින් දැම්මේ. (පිටුව ලෝඩ් වෙද්දිම මැසේජ් ටිකත් ලෝඩ් වෙනවා)
    });
        // ------------------ SaaS Plans Scripts ------------------

        function toggleBilling() {
            isAnnually = document.getElementById('billingToggle').checked;
            document.getElementById('text-monthly').classList.toggle('text-white', !isAnnually);
            document.getElementById('text-monthly').classList.toggle('text-gray-400', isAnnually);
            renderPlans(allPlansData);
        }

        function loadPlans() {
            fetch('/api/plans', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    allPlansData = data.plans;
                    renderPlans(allPlansData);
                } else {
                    document.getElementById('plans-container').innerHTML = `<p class="text-red-500">Error loading plans.</p>`;
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
            });
        }

        function renderPlans(plans) {
            if(plans.length === 0) {
                document.getElementById('plans-container').innerHTML = `<p class="text-gray-500">No plans found. Please add a new plan.</p>`;
                return;
            }
            let plansHTML = '';
            plans.forEach(plan => {
                let displayPrice = parseFloat(plan.price);
                let periodText = '/mo';
                let saveBadge = '';

                if (isAnnually) {
                    let annualPrice = (displayPrice * 12);
                    let discountedPrice = annualPrice * 0.85; 
                    displayPrice = discountedPrice;
                    periodText = '/yr';
                    saveBadge = `<div class="text-xs text-green-400 font-bold mb-1">Total Savings: Rs. ${(annualPrice - discountedPrice).toLocaleString()}</div>`;
                }

                let featuresList = '';
                if(plan.features && plan.features.length > 0) {
                    plan.features.forEach(f => {
                        featuresList += `<li><i class="fa-solid fa-check text-green-500 mr-2"></i>${f}</li>`;
                    });
                }
                
                let memberLimit = plan.max_members == 0 ? 'Unlimited Members' : `Up to ${plan.max_members} Members`;

                plansHTML += `
                    <div class="bg-panelBg p-6 rounded-2xl border border-gray-700 hover:border-brandOrange transition flex flex-col justify-between relative group">
                        <button onclick="deletePlan(${plan.id})" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 hidden group-hover:block transition">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-2">${plan.name}</h4>
                            ${saveBadge}
                            <h2 class="text-3xl font-bold text-brandOrange mb-1">Rs. ${displayPrice.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0})} <span class="text-sm font-normal text-gray-500">${periodText}</span></h2>
                            <p class="text-blue-400 text-sm font-semibold mb-6">${memberLimit}</p>
                            <ul class="text-gray-400 text-sm space-y-3 mb-6">${featuresList}</ul>
                        </div>
                    </div>
                `;
            });
            document.getElementById('plans-container').innerHTML = plansHTML;
        }

        function openAddPlanModal() {
            document.getElementById('addPlanModal').classList.remove('hidden');
            document.getElementById('addPlanModal').classList.add('flex');
        }

        function closeAddPlanModal() {
            document.getElementById('addPlanModal').classList.add('hidden');
            document.getElementById('addPlanModal').classList.remove('flex');
        }

        document.getElementById('addPlanForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const data = {
                name: document.getElementById('plan_name').value,
                price: document.getElementById('plan_price').value,
                max_members: document.getElementById('plan_members').value,
                features: document.getElementById('plan_features').value
            };

            fetch('/api/plans', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if(result.status === 'success') {
                    closeAddPlanModal();
                    document.getElementById('addPlanForm').reset();
                    loadPlans();
                } else {
                    alert('Error saving plan!');
                }
            });
        });

        function deletePlan(id) {
            if(confirm("Are you sure you want to delete this plan?")) {
                fetch('/api/plans/' + id, { 
                    method: 'DELETE', 
                    headers: { 'Authorization': 'Bearer ' + superAdminToken } 
                })
                .then(res => res.json())
                .then(result => { 
                    if(result.status === 'success') loadPlans(); 
                });
            }
        }

        // ------------------ Bulk SMS Packages Scripts ------------------

        function loadSmsPackages() {
            fetch('/api/sms-packages', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    let html = '';
                    data.data.forEach(pkg => {
                        let perSms = (parseFloat(pkg.price) / pkg.sms_count).toFixed(2);
                        html += `
                            <div class="bg-panelBg p-6 rounded-2xl border border-gray-700 relative group transition hover:border-blue-500">
                                <button onclick="deleteSms(${pkg.id})" class="absolute top-4 right-4 text-gray-500 hover:text-red-500 hidden group-hover:block transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-xl font-bold text-white">${pkg.name}</h4>
                                    <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-bold">Rs. ${perSms} / SMS</span>
                                </div>
                                <h2 class="text-3xl font-bold text-brandOrange mb-4">Rs. ${parseFloat(pkg.price).toLocaleString()}</h2>
                            </div>
                        `;
                    });
                    document.getElementById('sms-container').innerHTML = html || '<p class="text-gray-500">No SMS packages found.</p>';
                }
            });
        }

        function openAddSmsModal() {
            document.getElementById('addSmsModal').classList.remove('hidden');
            document.getElementById('addSmsModal').classList.add('flex');
        }

        function closeAddSmsModal() {
            document.getElementById('addSmsModal').classList.add('hidden');
            document.getElementById('addSmsModal').classList.remove('flex');
        }

        document.getElementById('addSmsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const data = {
                name: document.getElementById('sms_name').value,
                sms_count: document.getElementById('sms_count').value,
                price: document.getElementById('sms_price').value
            };

            fetch('/api/admin/sms-packages', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if(result.status === 'success') {
                    closeAddSmsModal();
                    document.getElementById('addSmsForm').reset();
                    loadSmsPackages();
                } else {
                    alert('Error saving SMS package!');
                }
            });
        });

        function deleteSms(id) {
            if(confirm("Are you sure you want to delete this SMS package?")) {
                fetch('/api/admin/sms-packages/' + id, { 
                    method: 'DELETE', 
                    headers: { 'Authorization': 'Bearer ' + superAdminToken } 
                })
                .then(res => res.json())
                .then(result => { 
                    if(result.status === 'success') loadSmsPackages(); 
                });
            }
        }

        // ------------------ SMS Requests Scripts ------------------

      function loadSmsRequests() {
            fetch('/api/admin/sms-purchases', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('requests-table');
                if(data.status === 'success' && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(req => {
                        let statusBadge = '';
                        let actionBtns = '';
                        
                        if(req.status === 'pending') {
                            statusBadge = `<span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold">Pending</span>`;
                            actionBtns = `
                                <button onclick="approveRequest(${req.id})" class="text-green-400 bg-green-400/10 hover:bg-green-400/20 px-3 py-1.5 rounded transition mr-2">Approve</button>
                                <button onclick="rejectRequest(${req.id})" class="text-red-400 bg-red-400/10 hover:bg-red-400/20 px-3 py-1.5 rounded transition">Reject</button>
                            `;
                        } else if(req.status === 'approved') {
                            statusBadge = `<span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-xs font-bold">Approved</span>`;
                            actionBtns = `<span class="text-gray-500 italic">Done</span>`;
                        } else {
                            statusBadge = `<span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-bold">Rejected</span>`;
                            actionBtns = `<span class="text-gray-500 italic">Done</span>`;
                        }

                        let slipUrl = req.slip_path ? `/storage/${req.slip_path}` : '#';

                        let gymNameHtml = req.gym ? 
                            `<a href="/manage-gyms" class="text-blue-400 hover:text-brandOrange hover:underline transition font-bold" title="View Gym Profile">
                                <i class="fa-solid fa-link text-xs mr-1 opacity-70"></i> ${req.gym.name}
                            </a>` : 
                            '<span class="text-gray-500">Unknown Gym</span>';

                        html += `
                            <tr class="hover:bg-gray-800/50 transition border-b border-gray-800">
                                <td class="px-6 py-4">${gymNameHtml}</td>
                                <td class="px-6 py-4 text-brandOrange font-semibold">${req.package ? req.package.name : 'Unknown Package'}</td>
                                <td class="px-6 py-4">
                                    <a href="${slipUrl}" target="_blank" class="text-blue-400 hover:underline"><i class="fa-solid fa-image mr-1"></i> View Slip</a>
                                </td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-right">${actionBtns}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No requests found.</td></tr>`;
                }
            });
        }
        
        function approveRequest(id) {
            if(!confirm('Approve this payment and add SMS to the gym?')) return;
            fetch(`/api/admin/sms-purchases/${id}/approve`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Approved! SMS added to Gym balance.');
                    loadSmsRequests();
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                }
            });
        }

        function rejectRequest(id) {
            if(!confirm('Reject this request?')) return;
            fetch(`/api/admin/sms-purchases/${id}/reject`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    loadSmsRequests();
                } else {
                    alert('Error: ' + (data.message || 'Something went wrong.'));
                }
            });
        }

        // ------------------ SaaS Requests Scripts ------------------

        function loadSaaSRequests() {
            fetch('/api/admin/subscription-requests', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                let tbody = document.getElementById('saas-requests-table');
                if(data.status === 'success' && data.requests.length > 0) {
                    let html = '';
                    data.requests.forEach(req => {
                        let date = new Date(req.created_at).toLocaleDateString();
                        let gymName = req.gym ? req.gym.name : 'Unknown Gym';
                        let planName = req.plan ? req.plan.name : 'Unknown Plan';
                        
                        let statusBadge = '';
                        let actionButtons = '';

                        if(req.status === 'pending') {
                            statusBadge = `<span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold border border-yellow-500/30">Pending</span>`;
                            actionButtons = `
                                <button onclick="processRequest(${req.id}, 'approve')" class="text-green-400 hover:text-green-300 bg-green-400/10 px-3 py-1.5 rounded transition mx-1"><i class="fa-solid fa-check"></i> Approve</button>
                                <button onclick="processRequest(${req.id}, 'reject')" class="text-red-400 hover:text-red-300 bg-red-400/10 px-3 py-1.5 rounded transition mx-1"><i class="fa-solid fa-xmark"></i> Reject</button>
                            `;
                        } else if(req.status === 'approved') {
                            statusBadge = `<span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-xs font-bold border border-green-500/30">Approved</span>`;
                            actionButtons = `<span class="text-gray-500 text-xs">Processed</span>`;
                        } else {
                            statusBadge = `<span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-bold border border-red-500/30">Rejected</span>`;
                            actionButtons = `<span class="text-gray-500 text-xs">Processed</span>`;
                        }

                        html += `
                            <tr class="hover:bg-gray-800/50 transition border-b border-gray-800">
                                <td class="px-6 py-4">${date}</td>
                                <td class="px-6 py-4 font-bold text-white">${gymName}</td>
                                <td class="px-6 py-4 text-brandOrange">${planName}</td>
                                <td class="px-6 py-4">
                                    <a href="/storage/${req.slip_path}" target="_blank" class="text-blue-400 hover:underline"><i class="fa-solid fa-image mr-1"></i> View Slip</a>
                                </td>
                                <td class="px-6 py-4">${statusBadge}</td>
                                <td class="px-6 py-4 text-right">${actionButtons}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No requests found.</td></tr>`;
                }
            });
        }

        function processRequest(id, action) {
            if(!confirm(`Are you sure you want to ${action} this request?`)) return;
            
            fetch(`/api/admin/subscription-requests/${id}/${action}`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
                loadSaaSRequests(); // ටේබල් එක අලුත් කරනවා
            });
        }

        // ------------------ General Scripts ------------------
        function logout() { 
            localStorage.removeItem('gym_super_admin_token'); 
            window.location.href = '/login'; 
        }

        const myToken = localStorage.getItem('gym_super_admin_token') || localStorage.getItem('gym_owner_token');

        function loadNotifications() {
            fetch('/api/notifications', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + myToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    let badge = document.getElementById('notif-badge');
                    if(data.unread_count > 0) {
                        badge.innerText = data.unread_count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }

                    let list = document.getElementById('notif-list');
                    let html = '';
                    if(data.notifications.length > 0) {
                        data.notifications.forEach(n => {
                            let bg = n.is_read ? 'bg-panelBg' : 'bg-gray-800/60';
                            let dot = n.is_read ? '' : '<span class="w-2 h-2 rounded-full bg-blue-500 absolute top-4 right-4"></span>';
                            
                            html += `
                                <div class="px-4 py-3 ${bg} hover:bg-gray-700 transition relative">
                                    ${dot}
                                    <p class="text-sm font-bold text-white mb-1">${n.title}</p>
                                    <p class="text-xs text-gray-400 leading-relaxed">${n.message}</p>
                                    <p class="text-[10px] text-gray-500 mt-2">${new Date(n.created_at).toLocaleString()}</p>
                                </div>
                            `;
                        });
                    } else {
                        html = '<p class="text-xs text-gray-500 text-center py-6">No new notifications.</p>';
                    }
                    list.innerHTML = html;
                }
            });
        }

        function toggleNotifications() {
            document.getElementById('notif-dropdown').classList.toggle('hidden');
        }

        function markAsRead(e) {
            e.stopPropagation(); 
            fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + myToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    loadNotifications(); 
                }
            });
        }



    </script>
</body>
</html>