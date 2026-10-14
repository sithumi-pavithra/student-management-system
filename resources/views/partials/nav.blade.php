<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg sticky top-0 z-40">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">

            {{-- Logo --}}
            <a href="{{ route('students.index') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold">Student Management</h1>
                    <p class="text-xs text-indigo-200">Admin Panel</p>
                </div>
            </a>

            {{-- User + Logout --}}
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-medium">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-indigo-200">Administrator</p>
                </div>
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-shield"></i>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>