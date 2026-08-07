<div class="bg-[#F4F7F9] font-sans p-4 min-h-screen text-slate-900">

    <!-- Header bar -->
    <div class="flex items-center justify-between pb-3.5 border-b border-slate-300 mb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Invoice Management</h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">List of purchase transactions and receipts</p>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 text-sm">
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Sorting Dropdown -->
            <select 
                wire:model.live="sortBy"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[160px] cursor-pointer"
            >
                <option value="latest">Latest Invoices</option>
                <option value="oldest">Oldest Invoices</option>
                <option value="highest">Highest Amount</option>
                <option value="lowest">Lowest Amount</option>
            </select>
        </div>

        <!-- Search input -->
        <div class="flex items-center gap-0 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search"
                placeholder="Search Invoice #, Product, Customer..." 
                class="px-3.5 py-2 bg-white border border-slate-300 rounded-l-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans w-80"
            />
            <button class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 border border-l-0 border-slate-300 rounded-r-xs cursor-pointer flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Invoices Table Card -->
    <div class="w-full">
        <div class="overflow-x-auto bg-white border border-slate-300 rounded-xs shadow-2xs">
            <table class="w-full text-left border-collapse text-base">
                <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-extrabold text-sm uppercase tracking-tight">
                    <tr>
                        <th class="py-2.5 px-3 border-r border-slate-300">Date</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Time</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Invoice#</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Product Name</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Customer Name</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Sales Person</th>
                        <th class="py-2.5 px-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-base">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50/50">
                            <!-- Date -->
                            <td class="py-3 px-3 border-r border-slate-200 font-mono text-slate-600 whitespace-nowrap">
                                {{ $invoice->created_at->format('d M Y') }}
                            </td>
                            <!-- Time -->
                            <td class="py-3 px-3 border-r border-slate-200 font-mono text-slate-500 whitespace-nowrap">
                                {{ $invoice->created_at->format('H:i') }}
                            </td>
                            <!-- Invoice# -->
                            <td class="py-3 px-3 border-r border-slate-200 font-mono font-bold text-slate-900 whitespace-nowrap">
                                <a href="/invoices/{{ $invoice->invoice_number }}" wire:navigate class="text-blue-600 hover:underline">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <!-- Product name -->
                            <td class="py-3 px-3 border-r border-slate-200 text-slate-700 font-medium truncate max-w-xs">
                                @php
                                    $itemNames = $invoice->items->pluck('product_name')->toArray();
                                    $count = count($itemNames);
                                @endphp
                                @if($count > 0)
                                    {{ $itemNames[0] }}
                                    @if($count > 1)
                                        <span class="text-xs text-blue-600 font-bold ml-1">(+{{ $count - 1 }} more)</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">N/A</span>
                                @endif
                            </td>
                            <!-- Customer Name -->
                            <td class="py-3 px-3 border-r border-slate-200 font-semibold text-slate-800 whitespace-nowrap">
                                @if($invoice->customer)
                                    <a href="/customers/{{ $invoice->customer->slug }}" wire:navigate class="text-blue-600 hover:underline font-bold">
                                        {{ $invoice->customer->name }}
                                    </a>
                                @else
                                    <span class="text-slate-500 font-semibold">Walk-in Customer</span>
                                @endif
                            </td>
                            <!-- Sales Person -->
                            <td class="py-3 px-3 border-r border-slate-200 font-medium text-slate-600 whitespace-nowrap">
                                {{ $invoice->user ? $invoice->user->name : 'System Admin' }}
                            </td>
                            <!-- Total -->
                            <td class="py-3 px-3 font-extrabold text-slate-900 text-right font-mono whitespace-nowrap">
                                €{{ number_format($invoice->grand_total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                No invoices found matching the filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>