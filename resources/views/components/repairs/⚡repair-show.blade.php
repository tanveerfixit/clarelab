<div class="bg-[#F8FAFC] font-sans p-5 min-h-screen text-slate-800">

    <!-- Modern Floating Toast Notification -->
    <div 
        x-data="{ show: false, message: '' }"
        x-on:show-toast.window="message = $event.detail.message; show = true; setTimeout(() => { show = false }, 4000)"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 translate-y-[-10px] scale-95"
        class="fixed top-20 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-md shadow-lg flex items-center gap-3 text-sm font-semibold border border-slate-700"
        style="display: none;"
    >
        <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shrink-0">
            ✓
        </div>
        <span x-text="message"></span>
        <button @click="show = false" class="text-slate-400 hover:text-white font-bold ml-2 text-xs">✕</button>
    </div>

    <!-- Header Navigation & Ticket Status Bar -->
    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="/repairs" wire:navigate class="p-2 rounded-sm bg-white hover:bg-slate-100 text-slate-700 transition border border-slate-300 shadow-2xs flex items-center gap-1 text-sm font-bold" title="Back to Repairs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Repairs</span>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Repair Ticket #{{ $ticket->ticket_number }}</h1>
                    <span class="px-2.5 py-0.5 rounded-sm text-xs font-bold uppercase tracking-wider
                        @if($ticket->status === 'Completed') bg-emerald-100 text-emerald-800 border border-emerald-300
                        @elseif($ticket->status === 'In Progress') bg-amber-100 text-amber-800 border border-amber-300
                        @elseif($ticket->status === 'Ready for Pickup') bg-emerald-100 text-emerald-900 border border-emerald-400 font-extrabold
                        @elseif($ticket->status === 'Received') bg-blue-100 text-blue-800 border border-blue-300
                        @else bg-slate-200 text-slate-800 border border-slate-300 @endif
                    ">
                        {{ $ticket->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Created on {{ $ticket->created_at->format('d M Y \a\t H:i') }} &bull; {{ $ticket->device_model }}</p>
            </div>
        </div>

        <!-- Status Dropdown Quick-Update -->
        <div class="flex items-center gap-2 bg-white border border-slate-300 rounded-sm p-1.5 shadow-2xs">
            <label for="status-selector" class="text-xs font-bold text-slate-600 pl-2">Status:</label>
            <select 
                id="status-selector"
                name="status_selector"
                wire:model.live="selectedStatus"
                wire:change="updateStatus"
                class="px-3 py-1.5 bg-slate-100 border-0 outline-none rounded-sm text-xs font-bold text-slate-900 cursor-pointer focus:bg-slate-200 transition"
            >
                <option value="Received">Received</option>
                <option value="In Progress">In Progress</option>
                <option value="Pending Parts">Pending Parts</option>
                <option value="Ready for Pickup">Ready for Pickup</option>
                <option value="Completed">Completed</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
    </div>

    <!-- Main Content Layout (65% / 35%) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

        <!-- LEFT COLUMN (2/3): Customer, Device, & Line Items Breakdown -->
        <div class="lg:col-span-2 space-y-5">

            <!-- 1. Customer & Device Overview Card -->
            <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
                <div class="bg-[#F8FAFC] px-5 py-3 border-b border-slate-300 flex items-center justify-between">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0a77c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Customer & Device Information</span>
                    </h2>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                    <!-- Customer Details -->
                    <div class="space-y-2 border-b md:border-b-0 md:border-r border-slate-200 pb-4 md:pb-0 md:pr-4">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Customer Name</span>
                            <span class="font-bold text-slate-900 text-base block mt-0.5">{{ $ticket->customer_name }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Phone Number</span>
                            <span class="font-mono text-slate-800 font-semibold block mt-0.5">{{ $ticket->phone_number }}</span>
                        </div>
                        @if($ticket->email_address)
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Email Address</span>
                                <span class="text-slate-700 font-medium block mt-0.5">{{ $ticket->email_address }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Device Fault Details -->
                    <div class="space-y-2">
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Device Model</span>
                            <span class="font-bold text-[#0a77c0] text-base block mt-0.5">{{ $ticket->device_model }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Problem Description</span>
                            <span class="text-slate-700 font-normal block mt-0.5 leading-relaxed">{{ $ticket->problem_description }}</span>
                        </div>
                        @if($ticket->part_needed)
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Part Needed / Installed</span>
                                <span class="text-slate-800 font-semibold block mt-0.5">{{ $ticket->part_needed }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. Repair Line Items Breakdown (Service & Spare Parts) -->
            <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
                <div class="bg-[#F8FAFC] px-5 py-3 border-b border-slate-300 flex items-center justify-between">
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-600 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0a77c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span>Repair Services & Spare Parts Breakdown</span>
                    </h2>
                    <span class="text-xs font-bold text-slate-500">{{ $ticket->items->count() }} Items</span>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-800 font-bold uppercase tracking-tight text-xs">
                            <tr>
                                <th class="py-2.5 px-4 border-r border-slate-300">Type</th>
                                <th class="py-2.5 px-4 border-r border-slate-300">Item / Part Description</th>
                                <th class="py-2.5 px-4 text-center border-r border-slate-300">Qty</th>
                                <th class="py-2.5 px-4 text-right border-r border-slate-300">Unit Price</th>
                                <th class="py-2.5 px-4 text-right border-r border-slate-300">Total</th>
                                <th class="py-2.5 px-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($ticket->items as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 border-r border-slate-200">
                                        @if($item->type === 'part')
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-sm text-[11px] font-bold">Spare Part</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded-sm text-[11px] font-bold">Labor Service</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-900 border-r border-slate-200">{{ $item->name }}</td>
                                    <td class="py-3 px-4 text-center font-mono border-r border-slate-200">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-right font-mono text-slate-600 border-r border-slate-200">€{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-slate-900 border-r border-slate-200">€{{ number_format($item->total_price, 2) }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <button 
                                            wire:click="removeItem({{ $item->id }})" 
                                            class="text-red-500 hover:text-red-700 font-bold text-xs p-1 rounded hover:bg-red-50 cursor-pointer"
                                            title="Remove item"
                                        >
                                            ✕
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-slate-400 font-mono text-sm">
                                        No repair items added yet. Add labor or parts below.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Add New Repair Item Form Bar -->
                <form wire:submit.prevent="addItem" class="p-5 bg-slate-50 border-t border-slate-300 space-y-4">
                    <span class="text-xs font-extrabold text-slate-700 uppercase tracking-wider block mb-1">+ Add Item or Service</span>
                    
                    <div class="space-y-4">
                        <!-- ROW 1: Item Type (1/3 width) & Item Name / Search Catalog (2/3 width) -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <!-- Item Type (1/3) -->
                            <div class="space-y-1.5 md:col-span-1">
                                <label for="new-item-type" class="text-xs font-extrabold text-slate-600 uppercase tracking-wider block">Item Type</label>
                                <select 
                                    id="new-item-type"
                                    name="new_item_type"
                                    wire:model.live="newItemType"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0a77c0] font-semibold cursor-pointer"
                                >
                                    <option value="service">Labor Service</option>
                                    <option value="part">Spare Part</option>
                                </select>
                            </div>

                            <!-- Item Description & Search (2/3) -->
                            <div class="space-y-1.5 md:col-span-2 relative" x-data="{ open: true }" @click.away="open = false">
                                <label for="parts-search-query" class="text-xs font-extrabold text-slate-600 uppercase tracking-wider block">Item Name / Search Catalog</label>
                                <input 
                                    id="parts-search-query"
                                    name="parts_search_query"
                                    type="text" 
                                    wire:model.live.debounce.150ms="partsSearchQuery"
                                    placeholder="Type to search or enter custom description..."
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0a77c0] font-medium"
                                    @focus="open = true"
                                    required
                                />

                                @if(!empty($searchResults))
                                    <div 
                                        x-show="open" 
                                        class="absolute z-50 left-0 right-0 top-[68px] bg-white border border-slate-300 rounded-sm shadow-xl max-h-56 overflow-y-auto divide-y divide-slate-100"
                                        style="display: none;"
                                    >
                                        @foreach($searchResults as $prod)
                                            <button 
                                                type="button"
                                                wire:click="selectSearchedProduct({{ $prod->id }})"
                                                @click="open = false"
                                                class="w-full px-4 py-2.5 text-left hover:bg-slate-100 transition text-xs font-semibold text-slate-900 flex justify-between items-center cursor-pointer"
                                            >
                                                <span class="truncate pr-2">{{ $prod->name }}</span>
                                                <span class="text-[#0a77c0] font-mono font-bold shrink-0">€{{ number_format($prod->selling_price, 2) }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- ROW 2: Price (€) & QTY Side by Side, + Add Item button aligned right -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end pt-1">
                            <!-- Price (€) -->
                            <div class="space-y-1.5 sm:col-span-4 md:col-span-3">
                                <label for="new-item-price" class="text-xs font-extrabold text-slate-600 uppercase tracking-wider block">Price (€)</label>
                                <input 
                                    id="new-item-price"
                                    name="new_item_price"
                                    type="number" 
                                    step="0.01" 
                                    wire:model="newItemPrice"
                                    placeholder="0.00"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 text-right focus:outline-none focus:border-[#0a77c0]"
                                    required
                                />
                            </div>

                            <!-- Qty -->
                            <div class="space-y-1.5 sm:col-span-3 md:col-span-2">
                                <label for="new-item-qty" class="text-xs font-extrabold text-slate-600 uppercase tracking-wider block text-center">Qty</label>
                                <input 
                                    id="new-item-qty"
                                    name="new_item_qty"
                                    type="number" 
                                    wire:model="newItemQty"
                                    placeholder="1"
                                    class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 text-center focus:outline-none focus:border-[#0a77c0]"
                                    required
                                />
                            </div>

                            <!-- Submit Button (Primary POS Blue bg-[#0a77c0] hover:bg-[#08629f]) -->
                            <div class="sm:col-span-5 md:col-span-7 flex justify-end">
                                <button 
                                    type="submit"
                                    class="w-full sm:w-auto px-6 py-2.5 bg-[#0a77c0] hover:bg-[#08629f] text-white font-bold text-sm rounded-sm transition shadow-2xs cursor-pointer flex items-center justify-center gap-2 uppercase tracking-wider whitespace-nowrap"
                                >
                                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Add Item</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <!-- RIGHT COLUMN (1/3): Financial Summary & Actions -->
        <div class="space-y-5">
            
            <!-- Financial Summary Card -->
            <div class="bg-white border border-slate-300 rounded-sm shadow-2xs p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Payment Summary</span>
                    <span class="font-mono text-xs text-slate-400 font-bold">#{{ $ticket->ticket_number }}</span>
                </div>

                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Total Quote:</span>
                        <span class="font-mono font-bold text-slate-900">€{{ number_format($ticket->total_quote, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Deposit Paid:</span>
                        <span class="font-mono font-bold text-emerald-600">- €{{ number_format($ticket->deposit_paid, 2) }}</span>
                    </div>

                    <div class="border-t border-slate-200 pt-3 flex justify-between items-center">
                        <span class="font-bold text-slate-900 text-sm">Balance Due:</span>
                        <span class="font-mono text-xl font-extrabold text-slate-900">
                            €{{ number_format($ticket->remaining_balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Card -->
            <div class="bg-white border border-slate-300 rounded-sm shadow-2xs p-4 space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 block">Actions</span>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <button 
                        wire:click="checkoutAtRegister"
                        class="w-full py-2 px-4 bg-[#0a77c0] hover:bg-[#08629f] text-white font-bold text-sm rounded-sm shadow-2xs transition inline-flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Pay at Register</span>
                    </button>

                    <button 
                        onclick="window.print()" 
                        class="w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-sm rounded-sm transition border border-slate-300 inline-flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Print Ticket</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>
