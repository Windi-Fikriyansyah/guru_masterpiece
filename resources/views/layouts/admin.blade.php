<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: '#6366f1',
                        secondary: '#4f46e5',
                        accent: '#818cf8',
                        dark: '#1e1b4b',
                    }
                }
            }
        }
    </script>
    
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar-link.active {
            background: linear-gradient(to right, #6366f1, #4f46e5);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans overflow-hidden">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 hidden lg:flex flex-col bg-white border-r border-slate-200">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i data-lucide="layout-dashboard"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-dark uppercase">GURU MASTER</span>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto max-h-[calc(100vh-250px)]">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
                    <i data-lucide="grid" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.rpp') }}" class="sidebar-link {{ request()->routeIs('admin.rpp') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span class="font-medium">RPM/RPP/Modul Ajar</span>
                </a>
                <a href="{{ route('admin.lkpd') }}" class="sidebar-link {{ request()->routeIs('admin.lkpd') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                    <span class="font-medium">LKPD/LKM</span>
                </a>
                <a href="{{ route('admin.text_summary') }}" class="sidebar-link {{ request()->routeIs('admin.text_summary') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span class="font-medium">Materi Ajar</span>
                </a>
                <a href="{{ route('admin.presentation') }}" class="sidebar-link {{ request()->routeIs('admin.presentation') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="presentation" class="w-5 h-5"></i>
                    <span class="font-medium">Materi Presentasi</span>
                </a>
                <a href="{{ route('admin.soal') }}" class="sidebar-link {{ request()->routeIs('admin.soal') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="help-circle" class="w-5 h-5"></i>
                    <span class="font-medium">Soal Otomatis</span>
                </a>
                
                <a href="{{ route('admin.rubric') }}" class="sidebar-link {{ request()->routeIs('admin.rubric') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    <span class="font-medium">Rubrik Penilaian</span>
                </a>
                <!-- <a href="{{ route('admin.ice_breaking') }}" class="sidebar-link {{ request()->routeIs('admin.ice_breaking') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                    <span class="font-medium">Ice Breaking</span>
                </a> -->
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    <span class="font-medium">CurhatBareng</span>
                </a>
            </nav>

            <div class="p-4 mt-auto">
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">System Status</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-slate-700">All systems normal</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span class="font-medium">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Header -->
            <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden text-slate-500">
                        <i data-lucide="menu"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-dark">@yield('header', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-4">
                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-slate-100 rounded-lg transition-colors">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>
                    <button class="p-2 text-slate-400 hover:text-primary hover:bg-slate-100 rounded-lg transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="h-8 w-[1px] bg-slate-200 mx-2"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-dark line-clamp-1">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 font-medium">Administrator</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-accent p-[2px]">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=6366f1" class="w-full h-full rounded-full border-2 border-white object-cover" alt="Avatar">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="flex-1 p-6 lg:p-10 max-w-7xl mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
