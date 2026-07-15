<aside class="w-64 bg-panelBg flex flex-col justify-between h-full border-r border-gray-800">
    <div>
        <div class="flex items-center gap-3 px-6 py-6 cursor-pointer">
            <i class="fa-solid fa-server text-brandOrange text-2xl"></i>
            <span class="text-xl font-bold tracking-wider">Gym SaaS</span>
        </div>

        <nav class="mt-4 px-4 space-y-2">
            <a href="/" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('/') || request()->is('dashboard') ? 'bg-brandOrange text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-chart-line w-5"></i>
                <span class="font-semibold">System Overview</span>
            </a>
            <a href="/manage-gyms" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('manage-gyms') ? 'bg-brandOrange text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-dumbbell w-5"></i>
                <span>Manage Gyms</span>
            </a>
            <a href="/gym-owners" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('gym-owners') ? 'bg-brandOrange text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-user-shield w-5"></i>
                <span>Gym Owners</span>
            </a>
            <a href="/subscriptions" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('subscriptions') ? 'bg-brandOrange text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
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