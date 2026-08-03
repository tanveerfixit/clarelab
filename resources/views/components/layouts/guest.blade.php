<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sign In - Phone Lab POS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F4F7F9] text-[#0f172a] antialiased min-h-screen flex flex-col font-sans justify-center items-center">

    <main class="w-full flex-1 flex flex-col justify-center items-center p-4">
        {{ $slot }}
    </main>

    <footer class="py-4 text-center text-xs text-slate-500 font-medium">
        &copy; {{ date('Y') }} ClareLab EPOS System &bull; All Rights Reserved
    </footer>

    @livewireScripts
</body>
</html>
