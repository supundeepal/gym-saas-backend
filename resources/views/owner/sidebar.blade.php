<aside class="w-64 bg-panelBg flex flex-col justify-between h-full border-r border-gray-800">
    <div>
        <div class="flex items-center gap-3 px-6 py-6 cursor-pointer">
            <i class="fa-solid fa-dumbbell text-brandBlue text-2xl"></i>
            <span class="text-xl font-bold tracking-wider">Gym Portal</span>
        </div>
       <nav class="mt-4 px-4 space-y-2">
            <a href="/owner-dashboard" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('owner-dashboard') ? 'bg-brandBlue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-chart-pie w-5"></i> <span>Dashboard</span>
            </a>
            <a href="/owner/members" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('owner/members') ? 'bg-brandBlue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-users w-5"></i> <span>Members</span>
            </a>
            <a href="/owner/memberships" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('owner/memberships') ? 'bg-brandBlue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-id-card w-5"></i> <span>Memberships</span>
            </a>
            <a href="/owner/attendance" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('owner/attendance') ? 'bg-brandBlue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-calendar-check w-5"></i> <span>Attendance</span>
            </a>
            <!-- SMS Alerts (දැන් තියෙන්නේ එකයි) -->
            <a href="/owner/sms" class="flex items-center gap-4 px-4 py-3 rounded-lg transition {{ request()->is('owner/sms') ? 'bg-brandBlue text-white shadow-lg' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
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