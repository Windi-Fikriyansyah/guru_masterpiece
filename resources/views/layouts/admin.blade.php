<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'guru masterpiece') }} - guru masterpiece</title>

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

                <a href="{{ route('paket-masterpiece') }}" class="sidebar-link {{ request()->routeIs('paket-masterpiece') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 {{ request()->routeIs('paket-masterpiece') ? '' : 'text-slate-500 hover:text-primary hover:bg-slate-50' }} rounded-xl transition-all duration-200">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span class="font-medium">Paket Masterpiece</span>
                </a>

                @auth
                @if(in_array(Auth::user()->package, ['standard', 'premium']))
                <a href="{{ route('admin.rpp') }}" class="sidebar-link {{ request()->routeIs('admin.rpp') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span class="font-medium">RPM/RPP/Modul Ajar</span>
                </a>
                @endif

                @if(Auth::user()->package === 'premium')
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
                <a href="{{ route('admin.curhat') }}" class="sidebar-link {{ request()->routeIs('admin.curhat') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                    <span class="font-medium">Refleksi Guru</span>
                </a>
                @endif

               

                <a href="{{ route('admin.group-access') }}" class="sidebar-link {{ request()->routeIs('admin.group-access') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium">
                        @if(Auth::user()->package === 'premium')
                            Akses Grup Premium
                        @elseif(Auth::user()->package === 'standard')
                            Akses Link Grup Eksklusif
                        @else
                            Akses Grup Komunitas
                        @endif
                    </span>
                </a>
                 <a href="{{ route('admin.video-tutorial') }}" class="sidebar-link {{ request()->routeIs('admin.video-tutorial') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 text-slate-500 hover:text-primary hover:bg-slate-50 rounded-xl transition-all duration-200">
                    <i data-lucide="play-circle" class="w-5 h-5"></i>
                    <span class="font-medium">Video Tutorial</span>
                </a>
                @endauth
            </nav>

            <div class="p-4 mt-auto">
                <div class="bg-slate-50 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">System Status</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-slate-700">All systems normal</span>
                    </div>
                </div>
                
                @auth
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span class="font-medium">Sign Out</span>
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white hover:bg-secondary rounded-xl transition-all duration-200 mt-4 shadow-md">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    <span class="font-medium">Sign In</span>
                </a>
                @endauth
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
                    @auth
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-dark line-clamp-1">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400 font-medium">Administrator</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary to-accent p-[2px]">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=fff&color=6366f1" class="w-full h-full rounded-full border-2 border-white object-cover" alt="Avatar">
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-primary hover:text-secondary px-4 py-2 rounded-lg border border-primary/20 hover:bg-primary/5 transition-all">
                            Masuk
                        </a>
                        
                    </div>
                    @endauth
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
    <!-- Floating WhatsApp Button -->
<a href="https://wa.me/6282210002059" 
   target="_blank"
   class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg transition-all duration-300 hover:scale-110">
    
    <svg xmlns="http://www.w3.org/2000/svg" 
         width="28" 
         height="28" 
         fill="currentColor" 
         viewBox="0 0 24 24">
        <path d="M20.52 3.48A11.86 11.86 0 0012.06 0C5.5 0 .17 5.33.17 11.89c0 2.1.55 4.16 1.6 5.98L0 24l6.32-1.65a11.9 11.9 0 005.74 1.46h.01c6.56 0 11.89-5.33 11.89-11.89 0-3.17-1.23-6.15-3.44-8.44zM12.07 21.3c-1.8 0-3.56-.48-5.1-1.39l-.36-.21-3.75.98 1-3.65-.23-.38a9.35 9.35 0 01-1.43-4.99c0-5.18 4.22-9.4 9.41-9.4 2.51 0 4.86.98 6.63 2.75a9.32 9.32 0 012.75 6.63c0 5.18-4.22 9.4-9.41 9.4zm5.16-7.05c-.28-.14-1.65-.82-1.9-.91-.25-.09-.43-.14-.61.14-.18.28-.7.91-.86 1.1-.16.18-.32.21-.6.07-.28-.14-1.17-.43-2.23-1.37-.82-.73-1.37-1.63-1.53-1.9-.16-.28-.02-.43.12-.57.13-.13.28-.32.43-.48.14-.16.18-.28.28-.46.09-.18.05-.35-.02-.48-.07-.14-.61-1.48-.84-2.02-.22-.53-.45-.46-.61-.47l-.52-.01c-.18 0-.48.07-.73.35-.25.28-.96.94-.96 2.3 0 1.35.99 2.66 1.13 2.85.14.18 1.96 3 4.76 4.2.66.28 1.18.45 1.58.57.66.21 1.26.18 1.73.11.53-.08 1.65-.67 1.88-1.31.23-.64.23-1.18.16-1.31-.07-.12-.25-.19-.53-.33z"/>
    </svg>
</a>

</body>
</html>
