<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPA Yönetim Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="text-gray-800 antialiased">
    <div class="flex h-screen overflow-hidden">
        
        @auth
        <!-- Mobile Overlay -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300">
            <div class="h-16 flex items-center justify-center border-b border-gray-800">
                <h1 class="text-xl font-bold tracking-wider"><i class="fa-solid fa-leaf text-green-400 mr-2"></i>SPA YÖNETİM</h1>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-user-tie w-6"></i> Resepsiyonistler
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.staff.*') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-users w-6"></i> Personeller
                    </a>
                    <a href="{{ route('admin.packages.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.packages.*') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-spa w-6"></i> Masaj Paketleri
                    </a>
                    <a href="{{ route('admin.cari') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.cari') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-wallet w-6"></i> Cari & Finans
                    </a>
                    <a href="{{ route('admin.tracking') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.tracking') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-clock-rotate-left w-6"></i> Sistem İzleme
                    </a>
                @elseif(Auth::user()->role === 'reception')
                    <a href="{{ route('reception.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('reception.dashboard') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-chart-pie w-6"></i> Dashboard
                    </a>
                    <a href="{{ route('reception.records.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('reception.records.*') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-book-open w-6"></i> Masaj Kayıtları
                    </a>
                    <a href="{{ route('reception.expenses.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('reception.expenses.*') ? 'bg-gray-800 border-l-4 border-green-400' : '' }}">
                        <i class="fa-solid fa-money-bill-wave w-6"></i> Günlük Giderler
                    </a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-gray-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-lg">
                        <i class="fa-solid fa-sign-out-alt mr-2"></i> Çıkış Yap
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
            @auth
            <!-- Top Header (Mobile) -->
            <header class="h-16 glass flex items-center justify-between px-6 md:justify-end shadow-sm z-10">
                <div class="md:hidden flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800"><i class="fa-solid fa-leaf text-green-500 mr-2"></i>SPA</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-600 bg-gray-200 px-3 py-1 rounded-full hidden sm:inline-block"><i class="fa-solid fa-user mr-1"></i> {{ Auth::user()->name }} {{ Auth::user()->surname }} ({{ ucfirst(Auth::user()->role) }})</span>
                </div>
            </header>
            @endauth

            <div class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                        <ul class="list-disc ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('-translate-x-full');
            if(sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            } else {
                overlay.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
