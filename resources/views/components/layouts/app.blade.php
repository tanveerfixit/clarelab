<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Phone Lab POS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F4F7F9] text-[#0f172a] antialiased h-screen overflow-hidden flex flex-col font-sans" x-data="{ userMenuOpen: false }">

    <!-- 1. App Header Bar (Dark slate bg #E2E8F0 matching activity log) -->
    <header class="h-16 bg-[#E2E8F0] border-b border-slate-300 flex items-center justify-between px-5 flex-shrink-0 z-30">
        
        <!-- Left: Monochrome B&W Microsoft Home SVG Icon + Business/Branch Name -->
        <div class="flex items-center gap-3 min-w-[240px]">
            <!-- Home Icon (No BG change on hover, color shifts to blue on hover) -->
            <a href="/" wire:navigate class="p-1 text-slate-800 hover:text-[rgb(2,133,181)] transition-colors duration-150 flex items-center justify-center group" title="Home Dashboard">
                <svg class="w-8 h-8 group-hover:scale-105 transition-transform duration-150" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            </a>

            <!-- Divider -->
            <div class="w-[1px] h-6 bg-slate-400"></div>

            <!-- Business & Branch Name Indicator -->
            <div class="flex flex-col">
                <span class="font-bold text-base text-slate-900 leading-tight">Phone Lab</span>
                <span class="text-xs text-slate-600 font-medium">Main Store (London)</span>
            </div>
        </div>

        <!-- Center: Global Search Input (White BG, No Border, No Shadow) -->
        <div class="flex-1 max-w-xl px-4">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Search here..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white border-0 shadow-none outline-none rounded-lg text-base text-slate-900 focus:outline-none focus:ring-0 placeholder:text-slate-400"
                />
                <svg class="w-5 h-5 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Right: Modern Minimalist Support & User Context Controls -->
        <div class="flex items-center gap-3">
            <!-- Support Action: Clean Ghost Icon Button -->
            <button class="px-3.5 py-2 rounded-lg bg-white/70 hover:bg-white text-slate-700 hover:text-slate-900 font-semibold text-sm transition-all duration-150 flex items-center gap-2 cursor-pointer border border-slate-300/80 shadow-2xs">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Support</span>
            </button>

            <!-- User Profile Dropdown: Sleek Integrated Pill -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg bg-white/70 hover:bg-white border border-slate-300/80 transition-all duration-150 cursor-pointer shadow-2xs focus:outline-none"
                >
                    <div class="w-7 h-7 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                        P
                    </div>
                    <span class="font-bold text-sm text-slate-800">Phone Lab</span>
                    <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div 
                    x-show="open" 
                    x-cloak
                    @click.away="open = false" 
                    x-transition
                    class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-xl border border-slate-200 py-1.5 z-50 text-base"
                >
                    <a href="/" wire:navigate class="block px-4 py-2.5 text-slate-800 hover:bg-slate-50 font-bold">Main Dashboard</a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="#" class="block px-4 py-2.5 text-slate-700 hover:bg-slate-50">Profile Settings</a>
                    <a href="#" class="block px-4 py-2.5 text-slate-700 hover:bg-slate-50">Branch Switcher</a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="#" class="block px-4 py-2.5 text-red-600 hover:bg-red-50">Log Out</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Outer Viewport Wrapper -->
    <div class="flex-1 flex min-h-0 overflow-hidden">

        <!-- 2. Authentic Dark Slate Sidebar (#2C3E50) Matching Reference Image -->
        <aside class="w-[120px] bg-[#2c3e50] text-[#cbd5e1] flex flex-col justify-between flex-shrink-0 border-r border-slate-700/50">
            <nav class="w-full divide-y divide-slate-600/40">
                
                <!-- Cash Register -->
                <a href="/register" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 transition text-center group {{ request()->is('register') ? 'bg-[#3b4e63] text-white font-bold' : 'hover:bg-[#34495e] text-[#cbd5e1]' }}">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Cash Register</span>
                </a>

                <!-- Repairs -->
                <a href="/repairs" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 transition text-center group {{ request()->is('repairs*') ? 'bg-[#3b4e63] text-white font-bold' : 'hover:bg-[#34495e] text-[#cbd5e1]' }}">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.4-2.4c.4-.4.4-1 0-1.3z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Repairs</span>
                </a>

                <!-- Invoices -->
                <a href="#" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 text-[#cbd5e1] hover:bg-[#34495e] hover:text-white transition text-center group">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Invoices</span>
                </a>

                <!-- Customers -->
                <a href="#" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 text-[#cbd5e1] hover:bg-[#34495e] hover:text-white transition text-center group">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM9 10H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm-8 4H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Customers</span>
                </a>

                <!-- Products -->
                <a href="/products" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 transition text-center group {{ request()->is('products*') ? 'bg-[#3b4e63] text-white font-bold' : 'hover:bg-[#34495e] text-[#cbd5e1]' }}">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M2 4h2v16H2V4zm4 0h1v16H6V4zm3 0h2v16H9V4zm4 0h1v16h-1V4zm3 0h2v16h-2V4zm3 0h1v16h-1V4z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Products</span>
                </a>

                <!-- Purchase Orders -->
                <a href="#" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 text-[#cbd5e1] hover:bg-[#34495e] hover:text-white transition text-center group">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Purchase<br/>Orders</span>
                </a>

                <!-- Orders -->
                <a href="#" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 text-[#cbd5e1] hover:bg-[#34495e] hover:text-white transition text-center group">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Orders</span>
                </a>

                <!-- Devices Inventory -->
                <a href="#" wire:navigate class="flex flex-col items-center justify-center py-4 px-2 text-[#cbd5e1] hover:bg-[#34495e] hover:text-white transition text-center group">
                    <svg class="w-9 h-9 mb-1 text-[#dbeefb] fill-current" viewBox="0 0 24 24">
                        <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/>
                    </svg>
                    <span class="text-sm font-sans leading-tight">Devices<br/>Inventory</span>
                </a>

            </nav>
        </aside>

        <!-- Dynamic Main Workspace Slot -->
        <main class="flex-1 overflow-auto bg-[#F4F7F9]">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
