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
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">Plans & SMS Packages</h2>
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
                    <!-- මේ බටන් එකේ අවුල හැදුවා -->
                    <button onclick="openAddSmsModal()" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add SMS Package
                    </button>
                </div>
                <div id="sms-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <p class="text-gray-500">Loading SMS Packages...</p>
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
            loadSmsPackages(); // Load SMS Packages on startup
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

        // ------------------ Bulk SMS Packages Scripts (වැරදි හැදුවා) ------------------

        function loadSmsPackages() {
            fetch('/api/sms-packages', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + superAdminToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    let html = '';
                    // මෙතන කලින් තිබ්බේ data.packages. ඒක data.data කියලා හැදුවා
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

            // මෙතන කලින් තිබ්බේ /api/sms-packages. ඒක /api/admin/sms-packages කියලා හැදුවා
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
                // මෙතනත් ලින්ක් එක /api/admin/sms-packages කියලා හැදුවා
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

        // ------------------ General Scripts ------------------
        function logout() { 
            localStorage.removeItem('gym_super_admin_token'); 
            window.location.href = '/login'; 
        }
    </script>
</body>
</html>