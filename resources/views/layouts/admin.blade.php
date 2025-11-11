<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOMIKU - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;            
        }
        .nav-item.active, .nav-item.active:hover {
            background-color: #415A77;
            color: #E0E1DD;
        }
        .nav-item:hover {
            background-color: #415A77;
        }
        html.dark .nav-item.active, html.dark .nav-item.active:hover {
            background-color: #4A5568; /* gray-700 */
        }
        html.dark .nav-item:hover {
            background-color: #4A5568; /* gray-700 */
        }
    </style>
</head>
<body class="bg-[#E0E1DD] dark:bg-gray-900">

<div class="flex h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="w-64 -translate-x-full lg:translate-x-0 fixed lg:relative z-20 flex-shrink-0 rounded-tl-3xl flex flex-col text-[#E0E1DD] bg-[#1B263B] transition-transform duration-300 ease-in-out">
        <div class="h-24 flex items-center justify-center">
            <h1 class="text-3xl font-bold text-white">KOMIKU</h1>
        </div>
        <nav class="flex-grow px-4">
            <a href="{{ url('/admin/dashboard') }}" class="nav-item flex items-center py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ url('/admin/users') }}" class="nav-item flex items-center py-3 px-4 rounded-lg transition-all duration-300 mt-2 transform hover:scale-105 {{ Request::is('admin/users*') ? 'active' : '' }}">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                <span>Manage User</span>
            </a>
            <a href="#" class="nav-item flex items-center py-3 px-4 rounded-lg transition-all duration-300 mt-2 transform hover:scale-105 {{ Request::is('admin/comics*') ? 'active' : '' }}">
                <i data-lucide="book-marked" class="w-5 h-5 mr-3"></i>
                <span>Manage Comic</span>
            </a>
            <a href="#" class="nav-item flex items-center py-3 px-4 rounded-lg transition-all duration-300 mt-2 transform hover:scale-105 {{ Request::is('admin/genres*') ? 'active' : '' }}">
                <i data-lucide="grid" class="w-5 h-5 mr-3"></i>
                <span>Manage Genre</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="h-24 flex items-center justify-between px-6 lg:px-10 rounded-tr-3xl bg-[#0D1B2A]">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="lg:hidden text-white mr-4">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h2 class="text-2xl lg:text-3xl font-bold text-white">@yield('title', 'Dashboard')</h2>
                    <p class="text-xs lg:text-sm text-gray-400">Today, {{ \Carbon\Carbon::now()->format('D, d M') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <button id="theme-toggle" class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i data-lucide="sun" class="w-6 h-6 hidden dark:block"></i>
                    <i data-lucide="moon" class="w-6 h-6 block dark:hidden"></i>
                </button>
                <button class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i data-lucide="settings" class="w-6 h-6"></i>
                </button>
                <button class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                </button>
                <a href="{{ url('/') }}" class="flex items-center space-x-2 text-white hover:text-gray-300">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </div>
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg min-h-full">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    // Sidebar toggle script
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });

    // Dark mode toggle script
    const themeToggleBtn = document.getElementById('theme-toggle');
    const htmlEl = document.documentElement;

    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        htmlEl.classList.add('dark');
    } else {
        htmlEl.classList.remove('dark');
    }

    themeToggleBtn.addEventListener('click', function() {
        htmlEl.classList.toggle('dark');
        if (htmlEl.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    lucide.createIcons();
</script>
@stack('scripts')
</body>
</html>