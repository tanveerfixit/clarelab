<div class="bg-[#F4F7F9] font-sans p-4 min-h-screen text-slate-900" x-data="{ showActivityLog: true }">

    <!-- Header bar -->
    <div class="flex items-center justify-between pb-3.5 border-b border-slate-300 mb-5">
        <div class="flex items-center gap-3">
            <a 
                href="/invoices" 
                wire:navigate
                class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-sm rounded-xs transition shadow-2xs flex items-center gap-1.5 cursor-pointer"
            >
                <span>←</span>
                <span>Sales Invoices</span>
            </a>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">View Invoice - {{ $invoice->invoice_number }}</h1>
        </div>

        <div>
            <!-- Print Action Dropdown -->
            <button 
                type="button" 
                class="px-4 py-2 bg-[#00bcd4] hover:bg-[#00acc1] text-white font-bold text-sm rounded-xs transition shadow-2xs flex items-center gap-1.5 cursor-pointer border border-[#00acc1]/50"
                onclick="window.print()"
            >
                <span>🖨️</span>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Info Section: Customer Info + Order Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5 text-sm">
        
        <!-- Customer Info Card -->
        <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-300 px-4 py-2.5 font-bold text-slate-800 flex items-center gap-2">
                <span>👤</span>
                <span>Customer Info</span>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Customer:</span>
                    <span class="font-semibold text-slate-900">
                        @if($invoice->customer)
                            <a href="/customers/{{ $invoice->customer->slug }}" wire:navigate class="text-blue-600 hover:underline font-bold">
                                {{ $invoice->customer->name }} 🔗
                            </a>
                        @else
                            Walk-in Customer
                        @endif
                    </span>
                </div>
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Email:</span>
                    <span class="font-semibold text-slate-800">{{ $invoice->customer ? $invoice->customer->email : 'N/A' }}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Phone No.:</span>
                    <span class="font-semibold text-slate-800 font-mono">{{ $invoice->customer ? $invoice->customer->phone : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Order Info Card -->
        <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden">
            <div class="bg-slate-50 border-b border-slate-300 px-4 py-2.5 font-bold text-slate-800 flex items-center gap-2">
                <span>📄</span>
                <span>Order Info</span>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Invoice No.</span>
                    <span class="font-mono font-bold text-slate-900">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Sales Person:</span>
                    <span class="font-semibold text-slate-800">{{ $invoice->user ? $invoice->user->name : 'System Admin' }}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-bold text-slate-500">Date:</span>
                    <span class="font-mono font-semibold text-slate-800">{{ $invoice->created_at->format('d-m-y') }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Items Table Block -->
    <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden mb-5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-base">
                <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-extrabold text-sm uppercase">
                    <tr>
                        <th class="py-2 px-3 border-r border-slate-300 w-12 text-center">#</th>
                        <th class="py-2 px-3 border-r border-slate-300">Description</th>
                        <th class="py-2 px-3 border-r border-slate-300 w-28 text-center">Time/Qty</th>
                        <th class="py-2 px-3 border-r border-slate-300 w-32 text-right">Unit Price</th>
                        <th class="py-2 px-3 w-32 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($invoice->items as $index => $item)
                        <tr class="hover:bg-slate-50/50">
                            <!-- Index -->
                            <td class="py-2.5 px-3 border-r border-slate-200 text-center font-medium text-slate-500">
                                {{ $index + 1 }}
                            </td>
                            <!-- Description -->
                            <td class="py-2.5 px-3 border-r border-slate-200 font-semibold text-slate-800">
                                {{ $item->product_name }}
                            </td>
                            <!-- Qty -->
                            <td class="py-2.5 px-3 border-r border-slate-200 text-center font-bold text-slate-900">
                                {{ $item->quantity }}
                            </td>
                            <!-- Unit Price -->
                            <td class="py-2.5 px-3 border-r border-slate-200 text-right font-mono font-semibold text-slate-700">
                                €{{ number_format($item->unit_price, 2) }}
                            </td>
                            <!-- Total -->
                            <td class="py-2.5 px-3 text-right font-mono font-extrabold text-slate-900">
                                €{{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 font-medium">
                                No items found in this invoice.
                            </td>
                        </tr>
                    @endforelse

                    <!-- Calculations Summary Rows -->
                    <tr class="bg-slate-50/50 border-t border-slate-300">
                        <td colspan="3" class="border-r border-slate-200"></td>
                        <td class="py-2 px-3 border-r border-slate-200 text-right font-bold text-slate-600 text-sm">Taxable Total :</td>
                        <td class="py-2 px-3 text-right font-mono font-extrabold text-slate-900">
                            €{{ number_format($invoice->subtotal - $invoice->discount, 2) }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50/50">
                        <td colspan="3" class="border-r border-slate-200"></td>
                        <td class="py-2 px-3 border-r border-slate-200 text-right font-bold text-slate-600 text-sm">Tax (0%) :</td>
                        <td class="py-2 px-3 text-right font-mono font-extrabold text-slate-900">
                            €{{ number_format($invoice->tax_amount, 2) }}
                        </td>
                    </tr>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <td colspan="3" class="border-r border-slate-200"></td>
                        <td class="py-2 px-3 border-r border-slate-200 text-right font-bold text-slate-600 text-sm">Grand Total :</td>
                        <td class="py-2 px-3 text-right font-mono font-extrabold text-slate-900 text-lg">
                            €{{ number_format($invoice->grand_total, 2) }}
                        </td>
                    </tr>
                    <!-- Payment Timestamp Metadata row -->
                    <tr class="bg-slate-50/30">
                        <td colspan="5" class="py-2.5 px-4 text-right text-xs text-slate-500 font-bold tracking-tight">
                            {{ $invoice->created_at->format('d-m-Y g:i a') }} {{ $invoice->payment_method }} Payment €{{ number_format($invoice->grand_total, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Refund Action Section -->
    <div class="flex justify-end mb-6">
        <button 
            type="button" 
            class="px-4 py-2 bg-[#ffeb3b] hover:bg-[#fdd835] text-slate-900 font-extrabold text-sm rounded-xs transition shadow-2xs cursor-pointer border border-yellow-400/60"
        >
            Create Refund
        </button>
    </div>

    <!-- Activity Log Expandable Panel -->
    <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden">
        <!-- Toggle Header -->
        <button 
            @click="showActivityLog = !showActivityLog" 
            class="w-full bg-slate-50 border-b border-slate-300 px-4 py-3 flex items-center justify-between font-bold text-slate-800 text-sm cursor-pointer"
        >
            <div class="flex items-center gap-2">
                <span x-show="showActivityLog">▼</span>
                <span x-show="!showActivityLog">▶</span>
                <span>Activity Log</span>
            </div>
            <div class="flex items-center gap-2" @click.stop>
                <select class="px-2.5 py-1.5 bg-white border border-slate-300 rounded-xs text-xs font-semibold text-slate-700">
                    <option>All Activities</option>
                </select>
                <button class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-xs text-xs font-bold text-slate-700 cursor-pointer">
                    Add New Note
                </button>
            </div>
        </button>

        <!-- Logs List (Alpine toggle) -->
        <div x-show="showActivityLog" class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="py-2 px-3 border-r border-slate-200 w-28">Date</th>
                        <th class="py-2 px-3 border-r border-slate-200 w-28">Time</th>
                        <th class="py-2 px-3 border-r border-slate-200 w-36">User</th>
                        <th class="py-2 px-3 border-r border-slate-200 w-44">Activity</th>
                        <th class="py-2 px-3">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <!-- Row 1: Invoice Created -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2.5 px-3 border-r border-slate-200 font-medium font-mono text-slate-500 whitespace-nowrap">
                            {{ $invoice->created_at->format('d-m-y') }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-medium font-mono text-slate-500 whitespace-nowrap">
                            {{ $invoice->created_at->format('g:i a') }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-semibold text-slate-800 whitespace-nowrap">
                            {{ $invoice->user ? $invoice->user->name : 'System Admin' }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-bold text-slate-900 whitespace-nowrap">
                            Invoice Created
                        </td>
                        <td class="py-2.5 px-3 font-medium text-slate-600">
                            Invoice {{ $invoice->invoice_number }} created for customer {{ $invoice->customer ? $invoice->customer->name : 'Walk-in Customer' }}
                        </td>
                    </tr>
                    <!-- Row 2: Payment Received -->
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-2.5 px-3 border-r border-slate-200 font-medium font-mono text-slate-500 whitespace-nowrap">
                            {{ $invoice->created_at->format('d-m-y') }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-medium font-mono text-slate-500 whitespace-nowrap">
                            {{ $invoice->created_at->format('g:i a') }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-semibold text-slate-800 whitespace-nowrap">
                            {{ $invoice->user ? $invoice->user->name : 'System Admin' }}
                        </td>
                        <td class="py-2.5 px-3 border-r border-slate-200 font-bold text-slate-900 whitespace-nowrap">
                            Payment Logged
                        </td>
                        <td class="py-2.5 px-3 font-medium text-slate-600">
                            Payment €{{ number_format($invoice->grand_total, 2) }} cleared using {{ $invoice->payment_method }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
