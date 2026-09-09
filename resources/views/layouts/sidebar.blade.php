<aside class="w-64 bg-gray-900 text-white min-h-screen p-4 flex flex-col justify-between">
    <!-- Bagian Atas: Logo & Menu Navigasi -->
    <div>
        <!-- Logo / Judul Brand -->
        <div class="flex items-center justify-center h-16 border-b border-gray-800 mb-6">
            <span class="text-xl font-bold tracking-wider">Admin System</span>
        </div>

        <!-- Daftar Menu Sidebar -->
        <nav class="space-y-2">
            <!-- Dashboard Menu -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>

            <!-- Menu Tambahan (Contoh: Manajemen User / Pengaturan) -->
            <a href="#"
               class="flex items-center px-4 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Manajemen User
            </a>
        </nav>
    </div>

    <!-- Bagian Bawah: Informasi User & Tombol Keluar -->
    <div class="border-t border-gray-800 pt-4">
        <div class="px-4 mb-3">
            <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-red-400 hover:bg-gray-800 hover:text-red-300 rounded-lg transition-colors text-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                Log Out
            </button>
        </form>
    </div>
</aside>
