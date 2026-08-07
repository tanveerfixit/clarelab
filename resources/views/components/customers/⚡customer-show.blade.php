<div class="bg-[#F4F7F9] font-sans p-4 min-h-screen text-slate-900">

    <!-- Header bar: Back Button & Customer Name -->
    <div class="flex items-center justify-between pb-3.5 border-b border-slate-300 mb-5">
        <div class="flex items-center gap-3">
            <a 
                href="/customers" 
                wire:navigate
                class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-sm rounded-xs transition shadow-2xs flex items-center gap-1.5 cursor-pointer"
            >
                <span>←</span>
                <span>Back to Customers</span>
            </a>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Customer Profile: {{ $customer->name }}</h1>
        </div>

        <div>
            <!-- Status Badge -->
            <span class="px-3 py-1 bg-blue-100 text-blue-800 border border-blue-200 text-xs font-bold uppercase rounded-full tracking-wider">
                {{ $customer->name === 'Walk-in Customer' ? 'System Default' : 'Active Account' }}
            </span>
        </div>
    </div>    <!-- Clean Tab Navigation -->
    <div class="w-full" x-data="{ activeTab: 'profile' }">
        <!-- Chrome-Style Tab Navigation -->
        <div class="flex items-end border-b border-slate-300 w-full mb-6 gap-[2px]">
            <button 
                @click="activeTab = 'profile'"
                :class="activeTab === 'profile' ? 'bg-white text-slate-800 border-t border-x border-slate-300 shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 border-t border-x border-slate-300/40'"
                class="px-5 py-2.5 text-sm font-semibold rounded-t-md transition duration-150 cursor-pointer flex items-center gap-2 border-b-0 -mb-[1px]"
            >
                <span>👤</span>
                <span>Customer Profile</span>
            </button>

            <button 
                @click="activeTab = 'repairs'"
                :class="activeTab === 'repairs' ? 'bg-white text-slate-800 border-t border-x border-slate-300 shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 border-t border-x border-slate-300/40'"
                class="px-5 py-2.5 text-sm font-semibold rounded-t-md transition duration-150 cursor-pointer flex items-center gap-2 border-b-0 -mb-[1px]"
            >
                <span>🛠️</span>
                <span>Repairing History</span>
                <span class="ml-0.5 px-2 py-0.5 text-xs font-bold rounded-full bg-slate-200/60 text-slate-700">
                    {{ $customer->repairs->count() }}
                </span>
            </button>

            <button 
                @click="activeTab = 'invoices'"
                :class="activeTab === 'invoices' ? 'bg-white text-slate-800 border-t border-x border-slate-300 shadow-xs font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 border-t border-x border-slate-300/40'"
                class="px-5 py-2.5 text-sm font-semibold rounded-t-md transition duration-150 cursor-pointer flex items-center gap-2 border-b-0 -mb-[1px]"
            >
                <span>🧾</span>
                <span>Invoices History</span>
                <span class="ml-0.5 px-2 py-0.5 text-xs font-bold rounded-full bg-slate-200/60 text-slate-700">
                    {{ $customer->invoices->count() }}
                </span>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="w-full">
            <!-- 1. Profile Tab Content -->
            <div x-show="activeTab === 'profile'">
                <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 max-w-xl">
                    <div class="flex items-center gap-5 pb-5 border-b border-slate-100 mb-5">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#0a77c0] to-blue-600 text-white flex items-center justify-center font-extrabold text-2xl shadow-sm">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="font-extrabold text-slate-900 text-2xl tracking-tight leading-tight">{{ $customer->name }}</h2>
                            @if($customer->company)
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $customer->company }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Modern Details List -->
                    <div class="divide-y divide-slate-100 text-sm">
                        <!-- Company -->
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-xs">Company</span>
                            <span class="text-slate-800 font-semibold text-right">{{ $customer->company ?: 'N/A' }}</span>
                        </div>
                        <!-- Primary Phone -->
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-xs">Primary Phone</span>
                            <span class="text-slate-900 font-bold font-mono text-right">{{ $customer->phone ?: 'N/A' }}</span>
                        </div>
                        <!-- Secondary Phone -->
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-xs">Secondary Phone</span>
                            <span class="text-slate-800 font-semibold font-mono text-right">{{ $customer->secondary_phone ?: 'N/A' }}</span>
                        </div>
                        <!-- Email -->
                        <div class="py-3.5 flex items-center justify-between gap-4">
                            <span class="font-bold text-slate-400 uppercase tracking-wider text-xs">Email Address</span>
                            @if($customer->email)
                                <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-right truncate max-w-[240px]">{{ $customer->email }}</a>
                            @else
                                <span class="text-slate-800 font-semibold text-right">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Repairs Tab Content -->
            <div x-show="activeTab === 'repairs'" x-cloak>
                <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden w-full">
                    <div class="bg-slate-50 border-b border-slate-300 px-4 py-3 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-sm tracking-tight flex items-center gap-2">
                            <span>🛠️</span>
                            <span>Active / Past Repair History</span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-base">
                            <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-extrabold text-sm uppercase">
                                <tr>
                                    <th class="py-2.5 px-3 border-r border-slate-200 w-1/4">Ticket No</th>
                                    <th class="py-2.5 px-3 border-r border-slate-200 w-1/3">Device Model</th>
                                    <th class="py-2.5 px-3 border-r border-slate-200 text-center">Status</th>
                                    <th class="py-2.5 px-3 border-r border-slate-200 text-right">Total Quote</th>
                                    <th class="py-2.5 px-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($customer->repairs as $repair)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-2.5 px-3 border-r border-slate-200 font-extrabold text-slate-800">
                                            {{ $repair->ticket_number }}
                                        </td>
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-slate-600 font-medium">
                                            {{ $repair->device_model }}
                                        </td>
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-center">
                                            <span class="inline-block px-2.5 py-1 rounded text-xs font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                                {{ $repair->status }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-right font-bold text-slate-800">
                                            £{{ number_format($repair->total_quote, 2) }}
                                        </td>
                                        <td class="py-2.5 px-3 text-center">
                                            <a 
                                                href="/repairs/{{ $repair->ticket_number }}" 
                                                wire:navigate
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 rounded-xs font-bold text-sm transition"
                                            >
                                                Open Ticket
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-400 font-medium">
                                            No active repair tickets logged.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Invoices Tab Content -->
            <div x-show="activeTab === 'invoices'" x-cloak>
                <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden w-full">
                    <div class="bg-slate-50 border-b border-slate-300 px-4 py-3 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 text-sm tracking-tight flex items-center gap-2">
                            <span>🧾</span>
                            <span>Purchase Invoices History</span>
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-base">
                            <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-extrabold text-sm uppercase">
                                <tr>
                                    <th class="py-2.5 px-3 border-r border-slate-200 w-1/4">Invoice No</th>
                                    <th class="py-2.5 px-3 border-r border-slate-200">Date Issued</th>
                                    <th class="py-2.5 px-3 border-r border-slate-200 text-center">Payment Method</th>
                                    <th class="py-2.5 px-3 text-right">Grand Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($customer->invoices as $invoice)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-2.5 px-3 border-r border-slate-200 font-extrabold text-slate-800">
                                            <a href="/invoices/{{ $invoice->invoice_number }}" wire:navigate class="text-blue-600 hover:underline">
                                                {{ $invoice->invoice_number }}
                                            </a>
                                        </td>
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-slate-500 font-medium">
                                            {{ $invoice->created_at->format('F d, Y H:i') }}
                                        </td>
                                        <td class="py-2.5 px-3 border-r border-slate-200 text-center font-semibold text-slate-700">
                                            {{ $invoice->payment_method }}
                                        </td>
                                        <td class="py-2.5 px-3 text-right font-bold text-slate-800">
                                            £{{ number_format($invoice->grand_total, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 font-medium">
                                            No purchase invoices registered.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
