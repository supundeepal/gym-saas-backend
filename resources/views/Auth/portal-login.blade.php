<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Portal Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#121417', panelBg: '#1c1f24', brandPrimary: '#3b82f6' } } } }
    </script>
</head>
<body class="bg-darkBg flex items-center justify-center h-screen font-sans text-white relative overflow-hidden">
    
    <!-- Background Design Element -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brandPrimary rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

    <div class="bg-panelBg p-10 rounded-2xl border border-gray-800 shadow-2xl w-full max-w-md z-10">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-dumbbell text-brandPrimary text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold tracking-wider">Gym Portal</h2>
            <p class="text-gray-500 text-sm mt-1">Sign in to manage your gym</p>
        </div>

        <form id="portalLoginForm" class="space-y-5">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Email Address</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-3 text-gray-500"></i>
                    <input type="email" id="email" class="w-full bg-darkBg border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:border-brandPrimary outline-none transition" required>
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-3 text-gray-500"></i>
                    <input type="password" id="password" class="w-full bg-darkBg border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:border-brandPrimary outline-none transition" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-brandPrimary hover:bg-blue-600 text-white font-bold py-3 rounded-lg transition shadow-lg mt-4">
                Login to Portal
            </button>
        </form>
    </div>

    <script>
    document.getElementById('portalLoginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                if (data.user.role === 'gym_owner' || data.user.role === 'admin' || data.user.role === 'owner' || data.user.role === 'gym_staff') {
                    localStorage.setItem('gym_owner_token', data.token);
                    
                    // 👈 මෙන්න මේ ලින්ක් එක හරියටම මේ විදිහටම තියෙන්න ඕන
                    window.location.href = '/owner-dashboard'; 
                } else {
                    alert('Access Denied: This portal is for Gym Owners and Staff only!');
                }
            }
        })
        .catch(err => {
            console.error("Login Error: ", err);
            alert("An error occurred during login. Please try again.");
        });
    });
</script>
</body>
</html>