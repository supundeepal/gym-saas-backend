<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - Gym SaaS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { darkBg: '#16171b', panelBg: '#222327', brandOrange: '#f55918' } } } }
    </script>
</head>
<body class="bg-darkBg flex items-center justify-center h-screen font-sans text-white">

    <div class="bg-panelBg p-10 rounded-2xl border border-gray-800 shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <i class="fa-solid fa-server text-brandOrange text-4xl mb-3"></i>
            <h2 class="text-2xl font-bold tracking-wider">Super Admin</h2>
            <p class="text-gray-500 text-sm mt-1">Gym SaaS Control Panel</p>
        </div>

        <form id="adminLoginForm" class="space-y-5">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Admin Email</label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-4 top-3 text-gray-500"></i>
                    <input type="email" id="email" class="w-full bg-darkBg border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:border-brandOrange outline-none transition" required>
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-3 text-gray-500"></i>
                    <input type="password" id="password" class="w-full bg-darkBg border border-gray-700 rounded-lg pl-12 pr-4 py-3 text-white focus:border-brandOrange outline-none transition" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-brandOrange hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition shadow-lg mt-4 flex justify-center items-center gap-2">
                Secure Login <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
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
                    // Super Admin ද කියලා තහවුරු කරනවා
                    if (data.user.role === 'super_admin') {
                        localStorage.setItem('gym_super_admin_token', data.token);
                        window.location.href = '/'; 
                    } else {
                        alert('Access Denied: You are not a Super Admin!');
                    }
                } else { alert('Login Failed: ' + data.message); }
            });
        });
    </script>
</body>
</html>