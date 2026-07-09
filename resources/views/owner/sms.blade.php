<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Alerts - Gym Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandBlue: '#3b82f6' } } } }
    </script>
</head>
<body class="bg-darkBg text-white font-sans flex h-screen overflow-hidden">

    <!-- අර අපි හදපු පොදු මෙනු එක -->
    @include('owner.sidebar')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="h-20 flex items-center justify-between px-8 bg-darkBg border-b border-gray-800">
            <h2 class="text-xl font-bold">SMS Alerts & Marketing</h2>
        </header>

        <div class="p-8 space-y-6">
            
            <!-- SMS Balance & Topup Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Balance Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 shadow-lg flex justify-between items-center relative overflow-hidden">
                    <i class="fa-solid fa-comment-sms text-9xl absolute -right-6 -bottom-6 text-white/10"></i>
                    <div>
                        <h3 class="text-blue-100 font-medium mb-1">Available SMS Balance</h3>
                        <h2 class="text-4xl font-bold text-white" id="smsBalance">Loading...</h2>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <i class="fa-solid fa-coins text-2xl text-white"></i>
                    </div>
                </div>

                <!-- Top up Card -->
                <div class="bg-panelBg rounded-2xl border border-gray-700 p-6 shadow-lg flex flex-col justify-center">
                    <h3 class="text-gray-300 font-medium mb-2">Need more SMS?</h3>
                    <p class="text-sm text-gray-500 mb-4">Top up your account to keep sending payment reminders and promotional offers to your members.</p>
                    <button class="bg-gray-700 hover:bg-gray-600 text-white py-2 px-4 rounded-lg font-bold transition w-full md:w-auto self-start">
                        <i class="fa-solid fa-cart-plus mr-2"></i> Buy SMS Packages
                    </button>
                </div>
            </div>

            <!-- Send SMS Form -->
            <div class="bg-panelBg rounded-2xl border border-gray-700 p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-paper-plane text-brandBlue mr-2"></i> Send New Message</h3>
                
                <form id="sendSmsForm" class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Send To</label>
                        <select id="sms_target" class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue">
                            <option value="all">All Active Members</option>
                            <option value="unpaid">Members with Pending Payments (Reminders)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Message Body <span class="text-xs text-gray-500 float-right" id="charCount">0/160 characters (1 SMS)</span></label>
                        <textarea id="sms_message" rows="4" placeholder="Type your message here... (e.g., Dear member, your gym fee is due...)" required class="w-full bg-darkBg border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-brandBlue"></textarea>
                    </div>
                    <div class="pt-2 text-right">
                        <button type="submit" class="bg-brandBlue hover:bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold transition shadow-lg shadow-blue-500/20">
                            Send Message <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <!-- JavaScript For API Integration -->
    <script>
        const ownerToken = localStorage.getItem('gym_owner_token');
        if (!ownerToken) window.location.href = '/portal/login';

        document.addEventListener('DOMContentLoaded', fetchSmsData);

        // SMS අකුරු ගාණ ගණන් කිරීම (160ක් තමයි එක මැසේජ් එකක්)
        document.getElementById('sms_message').addEventListener('input', function() {
            let length = this.value.length;
            let smsCount = Math.ceil(length / 160) || 1;
            document.getElementById('charCount').innerText = `${length}/160 characters (${smsCount} SMS)`;
        });

        // Backend එකෙන් SMS ගාණ ගැනීම
        function fetchSmsData() {
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
            })
            .catch(err => console.error(err));
        }

        // SMS යැවීම
        document.getElementById('sendSmsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if(!confirm('Are you sure you want to send this message? This will deduct from your SMS balance.')) return;

            const payload = {
                target: document.getElementById('sms_target').value,
                message: document.getElementById('sms_message').value
            };

            fetch('/api/owner/sms/send', { 
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ' + ownerToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Message sent successfully!');
                    document.getElementById('sendSmsForm').reset();
                    document.getElementById('charCount').innerText = "0/160 characters (1 SMS)";
                    fetchSmsData(); // Balance එක අප්ඩේට් කරනවා
                } else {
                    alert('Error: ' + (data.message || 'Failed to send SMS.'));
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