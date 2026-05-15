<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Retont Business</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#FF8528',
                            50:  '#FFF4EA',
                            100: '#FFE8D2',
                            500: '#FF8528',
                            600: '#E06800',
                            700: '#B35300',
                        },
                    },
                },
            },
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-gray-50 flex flex-col">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

        <!-- Logo & Brand -->
        <div class="mb-8 flex flex-col items-center gap-3">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md"
                 style="background-color:#FF8528;">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="text-center">
                <h1 class="text-2xl font-bold text-gray-900 leading-tight">Retont Business</h1>
                <p class="text-sm text-gray-500 mt-0.5">Admin Panel</p>
            </div>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-2xl px-8 py-10">
                @yield('content')
            </div>
        </div>

        <!-- Card footer area -->
        <p class="mt-8 text-xs text-gray-400 text-center">
            &copy; {{ date('Y') }} Retont Business. All rights reserved.
        </p>
    </div>

    @stack('scripts')
</body>
</html>
