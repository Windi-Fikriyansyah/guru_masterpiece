<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <footer class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 border-t border-gray-200 mt-12">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                    <div class="flex gap-6">
                        <a href="{{ route('privacy-policy') }}" class="hover:text-gray-800 transition-colors">
                            Kebijakan Privasi
                        </a>
                        <a href="{{ route('terms-of-service') }}" class="hover:text-gray-800 transition-colors">
                            Ketentuan Layanan
                        </a>
                    </div>
                    <div>
                        &copy; {{ date('Y') }} Guru Masterpiece. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
        <script>lucide.createIcons();</script>
    </body>
</html>
