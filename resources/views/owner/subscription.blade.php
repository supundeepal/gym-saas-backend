<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Subscription - Gym Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandBlue: '#3b82f6', brandOrange: '#f55918' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <!-- Owner Sidebar -->
    @include('owner.sidebar')

    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        
        <!-- 🔴 අලුත් Owner Header එක 🔴 -->
        @include('owner.header')

        <div class="p-8 space-y-8">
            
            <div class="mb-4">
                <h2 class="text-3xl font-bold text-white">My Subscription</h2>
                <p class="text-gray-400 mt-1">Manage your gym's current plan and renewals.</p>
            </div>

            <!-- Current Plan Status Card -->
            <div id="status-card" class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl p-8 border border-gray-700 shadow-xl relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
                <i class="fa-solid fa-medal text-9xl absolute -right-6 -top-6 text-white/5"></i>
                
                <div class="z-10">
                    <h3 class="text-gray-400 font-semibold mb-2 uppercase tracking-wider text-sm">Current Active Plan</h3>
                    <h1 class="text-4xl font-bold text-white mb-2" id="current_plan_name">Loading...</h1>
                    <p class="text-gray-400"><i class="fa-solid fa-calendar-days mr-2"></i> Expires on: <span id="expire_date_text" class="text-white font-bold">-</span></p>
                </div>

                <div class="z-10 text-center bg-darkBg/50 p-6 rounded-2xl border border-gray-700 min-w-[200px]">
                    <h3 class="text-gray-400 text-sm mb-1">Time Remaining</h3>
                    <div id="days_left_display" class="text-5xl font-bold text-green-400">-</div>
                    <p class="text-gray-500 text-sm mt-1">Days</p>
                </div>
            </div>

            <hr class="border-gray-800">

            <!-- Available Plans Section -->
            <div>
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-2xl font-bold flex items-center gap-2"><i class="fa-solid fa-cubes text-brandBlue"></i> Renew or Upgrade Plan</h3>
                        <p class="text-gray-400 text-sm mt-1">Select a plan, pay to our bank account, and upload the slip.</p>
                    </div>
                    
                    <!-- Toggle Switch -->
                    <div class="flex items-center gap-3 bg-panelBg px-4 py-2 rounded-full border border-gray-700">
                        <span class="text-sm font-semibold text-white" id="text-monthly">Monthly</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="billingToggle" class="sr-only peer" onchange="toggleBilling()">
                            <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brandBlue"></div>
                        </label>
                        <span class="text-sm font-bold text-gray-400 flex items-center gap-2" id="text-annually">
                            Annually <span class="bg-green-500/20 text-green-400 px-2 py-0.5 rounded text-[10px] uppercase tracking-wider">Save 15%</span>
                        </span>
                    </div>
                </div>
                
                <div id="plans-container" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <p class="text-gray-500">Loading plans...</p>
                </div>
            </div>

            <!-- History Table -->
            <div class="border-t border-gray-800 pt-8 mt-8">
                <h3 class="text-xl font-bold mb-4"><i class="fa-solid fa-clock-rotate-left text-brandBlue mr-2"></i> Subscription Request History</h3>
                <div class="bg-panelBg rounded-2xl border border-gray-700 overflow-hidden shadow-lg">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-darkBg text-gray-400 border-b border-gray-800">
                            <tr>
                                <th class="px-6 py-4">Date Submitted</th>
                                <th class="px-6 py-4">Requested Plan</th>
                                <th class="px-6 py-4">Billing Cycle</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody id="history-table" class="divide-y divide-gray-800 text-gray-300">
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Loading history...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Upload Slip Modal -->
    <div id="renewModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-panelBg p-8 rounded-2xl w-full max-w-md border border-gray-700 shadow-2xl relative">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold">Upload Payment Slip</h2>
                <button onclick="closeRenewModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <div class="mb-6 bg-blue-900/20 border border-blue-800 p-4 rounded-lg">
                <p class="text-sm text-blue-200">Plan: <strong id="modal_plan_name" class="text-white"></strong> (<span id="modal_billing_cycle"></span>)</p>
                <p class="text-xl font-bold text-white mt-1" id="modal_price"></p>
                <p class="text-xs text-blue-300 mt-2">Please transfer this exact amount and upload the slip.</p>
            </div>

            <form id="renewForm" class="space-y-4">
                <input type="hidden" id="selected_plan_id">
                <input type="hidden" id="selected_billing_cycle">
                
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Select Slip Image (JPG, PNG)</label>
                    <input type="file" id="slip_file" accept="image/jpeg, image/png, image/jpg" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-brandBlue file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-500/20 file:text-blue-400 hover:file:bg-blue-500/30">
                </div>
                
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="closeRenewModal()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-lg font-bold transition">Cancel</button>
                    <button type="submit" id="submitBtn" class="flex-1 bg-brandBlue hover:bg-blue-600 text-white py-2 rounded-lg font-bold transition">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        let allPlansData = [];
        let currentPlanData = null;
        let isAnnually = false;

        document.addEventListener('DOMContentLoaded', () => {
            fetchMyPlanData();
        });

        function toggleBilling() {
            isAnnually = document.getElementById('billingToggle').checked;
            document.getElementById('text-monthly').classList.toggle('text-white', !isAnnually);
            document.getElementById('text-monthly').classList.toggle('text-gray-400', isAnnually);
            document.getElementById('text-annually').classList.toggle('text-white', isAnnually);
            document.getElementById('text-annually').classList.toggle('text-gray-400', !isAnnually);
            renderPlans();
        }

        function fetchMyPlanData() {
            fetch('/api/owner/my-plan', {
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    
                    // Current Plan Status
                    currentPlanData = data.current_plan;
                    document.getElementById('current_plan_name').innerText = currentPlanData ? currentPlanData.name : 'No Active Plan';
                    document.getElementById('expire_date_text').innerText = data.expire_date || 'N/A';
                    
                    let daysLeftDisplay = document.getElementById('days_left_display');
                    let daysLeft = data.days_left;
                    
                    if(daysLeft > 10) {
                        daysLeftDisplay.innerText = daysLeft;
                        daysLeftDisplay.className = "text-5xl font-bold text-green-400";
                    } else if (daysLeft > 0) {
                        daysLeftDisplay.innerText = daysLeft;
                        daysLeftDisplay.className = "text-5xl font-bold text-yellow-500";
                    } else {
                        daysLeftDisplay.innerText = "Expired";
                        daysLeftDisplay.className = "text-4xl font-bold text-red-500";
                    }

                    allPlansData = data.all_plans || [];
                    renderPlans();
                    renderHistory(data.requests);
                }
            })
            .catch(err => console.error(err));
        }

        function renderPlans() {
            let plansContainer = document.getElementById('plans-container');
            let plansHtml = '';
            
            if(allPlansData.length > 0) {
                allPlansData.forEach(plan => {
                    let isCurrent = (currentPlanData && currentPlanData.id === plan.id);
                    let badge = isCurrent ? `<span class="absolute -top-3 -right-3 bg-green-500 text-white text-xs px-3 py-1 rounded-full shadow-lg">Current</span>` : '';
                    let btnText = isCurrent ? 'Renew Plan' : 'Upgrade';
                    let btnColor = isCurrent ? 'bg-gray-700 hover:bg-gray-600' : 'bg-brandBlue hover:bg-blue-600';

                    let displayPrice = parseFloat(plan.price);
                    let periodText = '/mo';
                    let saveBadge = '';
                    let billingCycleStr = 'monthly';

                    if (isAnnually) {
                        let annualPrice = (displayPrice * 12);
                        let discountedPrice = annualPrice * 0.85; 
                        displayPrice = discountedPrice;
                        periodText = '/yr';
                        billingCycleStr = 'annually';
                        saveBadge = `<div class="text-xs text-green-400 font-bold mb-1">Total Savings: Rs. ${(annualPrice - discountedPrice).toLocaleString()}</div>`;
                    }

                    let featuresList = '';
                    if(plan.features && plan.features.length > 0) {
                        plan.features.forEach(f => {
                            featuresList += `<li class="text-sm text-gray-400 mb-2"><i class="fa-solid fa-check text-green-500 mr-2"></i>${f}</li>`;
                        });
                    }

                    plansHtml += `
                        <div class="bg-panelBg p-6 rounded-2xl border ${isCurrent ? 'border-green-500' : 'border-gray-700'} relative flex flex-col justify-between hover:border-brandBlue transition">
                            ${badge}
                            <div>
                                <h4 class="text-xl font-bold text-white mb-2">${plan.name}</h4>
                                ${saveBadge}
                                <h2 class="text-3xl font-bold text-brandBlue mb-4">Rs. ${displayPrice.toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0})} <span class="text-sm text-gray-500 font-normal">${periodText}</span></h2>
                                <ul class="mb-6">${featuresList}</ul>
                            </div>
                            <button onclick="openRenewModal(${plan.id}, '${plan.name}', ${displayPrice}, '${billingCycleStr}')" class="w-full ${btnColor} text-white py-2 rounded-lg font-bold transition">
                                ${btnText}
                            </button>
                        </div>
                    `;
                });
                plansContainer.innerHTML = plansHtml;
            } else {
                plansContainer.innerHTML = '<p class="text-gray-500">No plans available.</p>';
            }
        }

        function renderHistory(requests) {
            let historyTbody = document.getElementById('history-table');
            if(requests && requests.length > 0) {
                let histHtml = '';
                requests.forEach(req => {
                    let date = new Date(req.created_at).toLocaleDateString();
                    
                    let statusBadge = '';
                    if(req.status === 'pending') statusBadge = `<span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold border border-yellow-500/30">Pending Approval</span>`;
                    else if(req.status === 'approved') statusBadge = `<span class="bg-green-500/20 text-green-500 px-3 py-1 rounded-full text-xs font-bold border border-green-500/30">Approved</span>`;
                    else statusBadge = `<span class="bg-red-500/20 text-red-500 px-3 py-1 rounded-full text-xs font-bold border border-red-500/30">Rejected</span>`;

                    let pName = req.plan ? req.plan.name : 'Unknown Plan';
                    let cycle = req.billing_cycle === 'annually' ? '<span class="text-blue-400 font-bold">Annually</span>' : 'Monthly';

                    histHtml += `
                        <tr class="hover:bg-gray-800/50 transition border-b border-gray-800">
                            <td class="px-6 py-4 text-gray-400">${date}</td>
                            <td class="px-6 py-4 font-bold text-white">${pName}</td>
                            <td class="px-6 py-4">${cycle}</td>
                            <td class="px-6 py-4">${statusBadge}</td>
                        </tr>
                    `;
                });
                historyTbody.innerHTML = histHtml;
            } else {
                historyTbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No request history found.</td></tr>`;
            }
        }

        function openRenewModal(planId, planName, price, billingCycle) {
            document.getElementById('selected_plan_id').value = planId;
            document.getElementById('selected_billing_cycle').value = billingCycle;
            
            document.getElementById('modal_plan_name').innerText = planName;
            document.getElementById('modal_billing_cycle').innerText = billingCycle.charAt(0).toUpperCase() + billingCycle.slice(1);
            document.getElementById('modal_price').innerText = 'Amount to Pay: Rs. ' + price.toLocaleString();

            document.getElementById('renewModal').classList.remove('hidden');
            document.getElementById('renewModal').classList.add('flex');
        }

        function closeRenewModal() {
            document.getElementById('renewModal').classList.add('hidden');
            document.getElementById('renewModal').classList.remove('flex');
            document.getElementById('renewForm').reset();
        }

        document.getElementById('renewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerText = "Uploading...";
            submitBtn.disabled = true;

            let formData = new FormData();
            formData.append('plan_id', document.getElementById('selected_plan_id').value);
            formData.append('billing_cycle', document.getElementById('selected_billing_cycle').value);
            formData.append('slip', document.getElementById('slip_file').files[0]);

            fetch('/api/owner/renew-plan', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerText = "Submit Request";
                submitBtn.disabled = false;
                if(data.status === 'success') {
                    alert(data.message);
                    closeRenewModal();
                    fetchMyPlanData(); 
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                submitBtn.innerText = "Submit Request";
                submitBtn.disabled = false;
                alert("An error occurred while uploading the slip.");
            });
        });

        function logout() {
            localStorage.removeItem('gym_owner_token');
            window.location.href = '/portal/login';
        }
    </script>
</body>
</html>