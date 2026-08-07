<div class="bg-[#F8FAFC] font-sans p-4 min-h-screen text-slate-800">
    
    <!-- Clean Minimalist Header Bar -->
    <div class="flex items-center justify-between pb-3.5">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Cash Register</h1>
    </div>

    <!-- Modern Enterprise Floating Toast Notification (Auto-Dismiss) -->
    <div 
        x-data="{ show: false, message: '' }"
        x-on:show-toast.window="message = $event.detail.message; show = true; setTimeout(() => { show = false }, 4000)"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-[-10px] scale-95"
        class="fixed top-20 right-6 z-50 bg-slate-900 text-white px-4.5 py-3 rounded-xl shadow-2xl flex items-center gap-3 text-sm font-semibold border border-slate-700"
        style="display: none;"
    >
        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shrink-0">
            ✓
        </div>
        <span x-text="message"></span>
        <button @click="show = false" class="text-slate-400 hover:text-white font-bold ml-2 text-xs">✕</button>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="mb-3.5 bg-[#DC3545]/10 border border-[#DC3545]/20 px-4 py-2.5 rounded-sm text-sm text-[#DC3545] flex justify-between items-center font-medium">
            <span>{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', null)" class="font-bold text-base">✕</button>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="flex flex-col lg:flex-row gap-4 items-start">

        <!-- 1. Left Section: Search, Auto-Expanding Cart Table, & Activity Log -->
        <div class="flex-1 flex flex-col min-w-0 space-y-4 w-full">
            
            <!-- Top Search & Action Buttons Container -->
            <div 
                x-data="{ showDropdown: true }" 
                x-on:click.outside="showDropdown = false"
                class="bg-white border border-slate-200 rounded-sm p-3.5 shadow-2xs relative z-30 flex gap-2.5"
            >
                <div class="relative flex-1">
                    <label for="pos-search-query" class="sr-only">Scan or Search Item</label>
                    <input 
                        id="pos-search-query"
                        name="pos_search_query"
                        type="text" 
                        wire:model.live.debounce.150ms="searchQuery" 
                        x-on:focus="showDropdown = true"
                        x-on:keydown.escape="showDropdown = false"
                        placeholder="Scan or Search Item..." 
                        class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-sm text-base text-slate-900 focus:outline-none focus:ring-1 focus:ring-[#0a77c0] focus:border-transparent transition placeholder:text-slate-400"
                    />
                    <svg class="w-5 h-5 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Action Buttons: Standard Gray '+' & Four-Square Grid Button -->
                <button class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-sm border border-slate-300 transition font-bold text-lg flex items-center justify-center cursor-pointer shadow-2xs" title="Add Custom Item">
                    <span>+</span>
                </button>
                <button 
                    wire:click="toggleSpeedGrid" 
                    class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-sm border transition flex items-center justify-center cursor-pointer shadow-2xs {{ $showSpeedGrid ? 'border-slate-900 bg-slate-100 text-slate-900 font-bold' : 'border-slate-300' }}" 
                    title="Toggle Speed Grid View"
                >
                    <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>

                <!-- Real-Time Product Search Results Dropdown -->
                @if(count($this->searchResults) > 0)
                    <div 
                        x-show="showDropdown" 
                        wire:key="search-results-overlay-container" 
                        class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-300 shadow-lg z-50 max-h-72 overflow-y-auto font-sans"
                    >
                        @foreach($this->searchResults as $res)
                            <div 
                                wire:key="search-item-{{ $res['id'] }}"
                                wire:click="selectProduct({{ $res['id'] }})" 
                                class="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-dotted border-gray-300 text-base text-gray-800 flex items-center justify-between"
                            >
                                <span class="truncate">
                                    <span class="font-normal text-gray-900">{{ $res['name'] }}</span>
                                    <span class="text-gray-600"> - SKU {{ $res['sku'] ?? $res['id'] }}</span>
                                    <span class="font-bold text-gray-900 ml-1">({{ $res['stock_qty'] ?? '*' }})</span>
                                </span>
                                <span class="font-bold text-gray-900 font-mono ml-4 shrink-0 text-base">€{{ number_format($res['selling_price'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Decoupled Speed Grid Touchscreen Quick Buttons Component (Toggled via Grid Button) -->
            @if($showSpeedGrid)
                <livewire:pos.speed-grid />
            @endif

            <!-- Auto-Expanding Cart Table Card -->
            <div class="bg-white border border-slate-200 rounded-sm shadow-2xs flex flex-col transition-all duration-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-base">
                        <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-800 font-bold uppercase tracking-tight text-xs">
                            <tr>
                                <th class="py-3.5 px-3.5 w-12 text-center border-r border-slate-300">#</th>
                                <th class="py-3.5 px-4 border-r border-slate-300">Description</th>
                                <th class="py-3.5 px-3.5 text-center border-r border-slate-300 w-36">Need/Have/OnPO</th>
                                <th class="py-3.5 px-3.5 text-center border-r border-slate-300 w-32">Time/Qty</th>
                                <th class="py-3.5 px-3.5 text-right border-r border-slate-300 w-32">Unit Price</th>
                                <th class="py-3.5 px-3.5 text-right border-r border-slate-300 w-32">Total</th>
                                <th class="py-3.5 px-3 text-center w-12">
                                    <svg class="w-4.5 h-4.5 text-slate-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-slate-800 text-base">
                            @forelse($cart as $index => $item)
                                <tr wire:key="cart-item-{{ $item['id'] ?? $index }}" class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3.5 px-3.5 text-center font-bold text-slate-600 border-r border-slate-200 text-base">{{ $index + 1 }}</td>
                                    <td class="py-3.5 px-4 border-r border-slate-200 hover:underline cursor-pointer text-base">
                                        <div wire:click="openCartItemModal({{ $index }})" class="font-semibold text-blue-600">{{ $item['name'] }}</div>
                                        @if(!empty($item['description']))
                                            <div class="text-sm text-gray-500 font-light italic mt-0.5">{{ $item['description'] }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3.5 text-center border-r border-slate-200 text-slate-400 font-mono text-sm">--</td>
                                    <td class="py-3.5 px-3.5 text-center border-r border-slate-200 font-bold">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="updateQuantity({{ $index }}, -1)" class="w-7 h-7 rounded bg-slate-100 hover:bg-slate-200 text-base font-bold text-slate-700 flex items-center justify-center cursor-pointer">-</button>
                                            <span class="w-7 text-center font-bold text-base">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity({{ $index }}, 1)" class="w-7 h-7 rounded bg-slate-100 hover:bg-slate-200 text-sm font-bold text-slate-700 flex items-center justify-center cursor-pointer">+</button>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3.5 text-right border-r border-slate-200 font-mono text-base">€{{ number_format($item['price'], 2) }}</td>
                                    <td class="py-3.5 px-3.5 text-right border-r border-slate-200 font-bold text-slate-900 font-mono text-base">
                                        @php
                                            $sub = $item['price'] * $item['quantity'];
                                            $disc = 0.00;
                                            if (isset($item['discount_amount']) && $item['discount_amount'] > 0) {
                                                if ($item['discount_type'] === 'percentage') {
                                                    $disc = $sub * ($item['discount_amount'] / 100);
                                                } else {
                                                    $disc = min($item['discount_amount'], $sub);
                                                }
                                            }
                                            $tot = max(0.00, $sub - $disc);
                                        @endphp
                                        €{{ number_format($tot, 2) }}
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-2.5">
                                            <button wire:click="openCartItemModal({{ $index }})" class="text-slate-400 hover:text-blue-600 transition cursor-pointer inline-flex items-center justify-center" title="Edit Item Details">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            <button wire:click="removeFromCart({{ $index }})" class="text-slate-400 hover:text-red-600 transition cursor-pointer inline-flex items-center justify-center" title="Remove Item">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400 font-mono text-sm">
                                        Cart is empty. Search products above.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Log Card -->
            <div class="bg-white border border-slate-200 rounded-sm shadow-2xs flex flex-col overflow-hidden">
                <!-- Activity Log Bar -->
                <div class="bg-[#E2E8F0] px-4 py-2.5 border-b border-slate-300 flex items-center justify-between">
                    <div 
                        wire:click="toggleActivityLog" 
                        class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition select-none font-bold text-xs text-slate-800"
                    >
                        <span class="text-slate-600 font-bold transform transition-transform duration-200 {{ $activityLogExpanded ? '' : 'rotate-180' }}">▲</span>
                        <span class="text-xs uppercase tracking-wide">Activity Log</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <select class="bg-white border border-slate-300 rounded-sm px-2.5 py-1 text-xs text-slate-700 focus:outline-none font-medium">
                            <option>All Activities</option>
                            <option>Sales Created</option>
                        </select>
                        <button class="px-3 py-1 bg-white hover:bg-slate-50 border border-slate-300 rounded-sm text-xs font-semibold text-slate-700 transition cursor-pointer shadow-2xs">
                            Add Digital Signature
                        </button>
                        <button class="px-3 py-1 bg-white hover:bg-slate-50 border border-slate-300 rounded-sm text-xs font-semibold text-slate-700 transition cursor-pointer shadow-2xs">
                            Add New Note
                        </button>
                    </div>
                </div>

                @if($activityLogExpanded)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-bold uppercase tracking-tight text-xs">
                                <tr>
                                    <th class="py-2.5 px-3.5 border-r border-slate-200 w-28">Date</th>
                                    <th class="py-2.5 px-3.5 border-r border-slate-200 w-28">Time</th>
                                    <th class="py-2.5 px-4 border-r border-slate-200 w-36">User</th>
                                    <th class="py-2.5 px-4 border-r border-slate-200 w-40">Activity</th>
                                    <th class="py-2.5 px-4">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-800 text-xs">
                                @forelse($activityLogs as $log)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="py-2.5 px-3.5 border-r border-slate-200 font-mono">{{ $log['date'] }}</td>
                                        <td class="py-2.5 px-3.5 border-r border-slate-200 font-mono text-slate-600">{{ $log['time'] }}</td>
                                        <td class="py-2.5 px-4 border-r border-slate-200 font-medium text-slate-900">{{ $log['user'] }}</td>
                                        <td class="py-2.5 px-4 border-r border-slate-200 font-medium text-slate-800">{{ $log['activity'] }}</td>
                                        <td class="py-2.5 px-4 font-mono text-slate-700">{{ $log['details'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center text-slate-400 text-xs italic">
                                            No recent transaction activity
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Integrated Right-Hand Sidebar Payment System (Supporting Multi-Line Split Payments) -->
        <div class="w-full lg:w-[420px] bg-white border border-slate-200 rounded-sm shadow-2xs p-4 flex flex-col space-y-4 shrink-0 font-sans">
            
            <!-- A. CUSTOMER SECTION -->
            <div class="space-y-2 pb-3 border-b border-slate-200">
                <div class="flex justify-between items-center text-xs font-extrabold uppercase tracking-wider text-slate-700">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>CUSTOMER</span>
                    </div>
                    <button wire:click="openCustomerCreateModal" class="text-[#0a77c0] hover:underline cursor-pointer text-xs font-extrabold">+ NEW</button>
                </div>

                <div class="relative">
                    <label for="pos-customer-search" class="sr-only">Search phone or name</label>
                    <input 
                        id="pos-customer-search"
                        name="pos_customer_search"
                        type="text" 
                        wire:model.live.debounce.150ms="customerSearch" 
                        placeholder="Search phone or name..." 
                        class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-[#0a77c0] placeholder:text-slate-400 font-sans"
                        @if($selectedCustomerId) disabled @endif
                    />
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>

                    @if($selectedCustomerId)
                        <button 
                            type="button"
                            wire:click="clearSelectedCustomer" 
                            class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 font-bold cursor-pointer text-sm"
                        >
                            ✕
                        </button>
                    @endif

                    <!-- Autocomplete dropdown -->
                    @if(!empty($this->customerSearchResults))
                        <div class="absolute left-0 right-0 z-50 mt-1 bg-white border border-slate-350 rounded-sm shadow-lg max-h-60 overflow-y-auto divide-y divide-slate-100 font-sans">
                            @foreach($this->customerSearchResults as $cust)
                                <button 
                                    type="button"
                                    wire:click="selectCustomer({{ $cust['id'] }})"
                                    class="w-full text-left px-3.5 py-2.5 hover:bg-slate-50 text-sm text-slate-800 transition flex flex-col cursor-pointer"
                                >
                                    <span class="font-extrabold text-slate-900">{{ $cust['name'] }}</span>
                                    <span class="text-xs text-slate-500 font-semibold mt-0.5">
                                        @if($cust['phone']) 📞 {{ $cust['phone'] }} @endif
                                        @if($cust['email']) • ✉️ {{ $cust['email'] }} @endif
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- B. SUMMARY SECTION -->
            <div class="space-y-3 pb-3 border-b border-slate-200">
                <div class="flex items-center gap-1.5 text-sm font-extrabold uppercase tracking-wider text-slate-700">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>SUMMARY</span>
                </div>

                <div class="space-y-2 text-base font-sans">
                    <div class="flex justify-between items-center text-slate-700">
                        <span>Subtotal</span>
                        <span class="font-mono font-bold text-slate-900">€{{ number_format($this->subtotal, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-700">
                        <div class="flex items-center gap-1">
                            <label for="pos-tax-selection">Tax</label>
                            <select 
                                id="pos-tax-selection"
                                name="pos_tax_selection"
                                wire:model.live="taxSelection" 
                                class="text-xs bg-gray-50 border border-gray-200 rounded px-1 py-0.5 text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-900 cursor-pointer font-sans"
                            >
                                <option value="0.000_exclusive">Vat0 (0.000%)</option>
                                <option value="13.000_inclusive">Product Tax (13.000% Inclusive)</option>
                                <option value="23.000_inclusive">Vat2 (23.000% Inclusive)</option>
                            </select>
                        </div>
                        <span class="font-mono font-bold text-slate-900">€{{ number_format($this->taxAmount, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-slate-700">
                        <span>Discount</span>
                        <span class="font-mono font-bold text-slate-900">-€{{ number_format($this->discountAmount, 2) }}</span>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-200 flex justify-between items-center">
                    <span class="font-extrabold text-lg uppercase text-slate-900">TOTAL AMOUNT</span>
                    <span class="font-extrabold text-3xl text-slate-900 font-mono">€{{ number_format($this->grandTotal, 2) }}</span>
                </div>
            </div>

            <!-- C. PAYMENT -->
            <div class="space-y-4 pb-4">
                <div class="text-sm font-semibold uppercase tracking-wide text-gray-400">Payment</div>

                <!-- iOS-style Segmented Control -->
                <div class="bg-gray-100 p-1 rounded-lg flex gap-0.5">
                    <button 
                        wire:click="setSidebarPaymentMethod('Debit Card')"
                        class="flex-1 py-2.5 text-sm font-medium rounded-md text-center cursor-pointer transition-all duration-150 {{ $sidebarPaymentMethod === 'Debit Card' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >Card</button>
                    <button 
                        wire:click="setSidebarPaymentMethod('Cash')"
                        class="flex-1 py-2.5 text-sm font-medium rounded-md text-center cursor-pointer transition-all duration-150 {{ $sidebarPaymentMethod === 'Cash' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >Cash</button>
                    <button 
                        wire:click="setSidebarPaymentMethod('Other')"
                        class="flex-1 py-2.5 text-sm font-medium rounded-md text-center cursor-pointer transition-all duration-150 {{ $sidebarPaymentMethod === 'Other' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                    >Other</button>
                </div>

                <!-- Applied Payments -->
                @if(!empty($sidebarAppliedPayments))
                    <div class="space-y-0">
                        @foreach($sidebarAppliedPayments as $idx => $p)
                            <div wire:key="pay-{{ $idx }}" class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <span class="text-base text-gray-600">{{ $p['method'] }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-base font-semibold text-gray-900 font-mono">€{{ number_format($p['amount'], 2) }}</span>
                                    <button wire:click="removeSidebarPayment({{ $idx }})" class="text-gray-300 hover:text-red-500 transition-colors duration-150 cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Amount Input -->
                @if($this->grandTotal > 0 && $this->sidebarRemainingBalance > 0)
                    <div class="relative">
                        <label for="pos-sidebar-payment-input" class="sr-only">Payment Amount</label>
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-300 font-light">€</span>
                        <input 
                            id="pos-sidebar-payment-input"
                            name="pos_sidebar_payment_input"
                            type="number" 
                            step="0.01" 
                            wire:model.live="sidebarPaymentInput"
                            placeholder="{{ number_format($this->sidebarRemainingBalance, 2) }}"
                            class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-lg text-2xl font-medium font-mono text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition-all duration-150"
                        />
                    </div>
                @endif

                <!-- Single Dynamic Action Button -->
                @if($this->grandTotal > 0)
                    @if($this->isSidebarFullyPaid)
                        <button 
                            wire:click="openCheckoutModal"
                            class="w-full py-3.5 bg-gray-900 hover:bg-black text-white rounded-lg text-base font-semibold cursor-pointer transition-all duration-150 flex items-center justify-center gap-2"
                        >
                            Review Sale
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    @else
                        <button 
                            wire:click="applySidebarPayment"
                            class="w-full py-3.5 bg-gray-900 hover:bg-black text-white rounded-lg text-base font-semibold cursor-pointer transition-all duration-150"
                        >
                            Apply €{{ number_format(floatval($sidebarPaymentInput ?: $this->sidebarRemainingBalance), 2) }} as {{ $sidebarPaymentMethod }}
                        </button>
                    @endif
                @endif

                <!-- Discard -->
                @if(!empty($cart))
                    <button 
                        wire:click="clearCart"
                        wire:confirm="Are you sure you want to discard this transaction? All items and payments will be cleared."
                        class="w-full py-2.5 text-base text-red-500 hover:text-white hover:bg-red-500 border border-red-500 rounded-lg font-medium cursor-pointer transition-all duration-150 text-center"
                    >
                        Discard Transaction
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Thermal Receipt Modal Simulation (80mm) -->
    @if($showReceiptModal && $receiptData)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-sm shadow-2xl max-w-sm w-full overflow-hidden flex flex-col max-h-[90vh]">
                
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center print:hidden">
                    <h4 class="font-bold text-base text-slate-900">Thermal Receipt Preview</h4>
                    <button wire:click="closeReceiptModal" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="p-6 overflow-auto font-mono text-xs text-black bg-white" id="thermal-receipt">
                    <div class="text-center space-y-1 mb-4">
                        <div class="font-bold text-base tracking-wider uppercase">PHONE LAB POS</div>
                        <div>Main Store, London</div>
                        <div class="pt-2 text-[10px] text-slate-500">VAT Reg: GB123456789</div>
                    </div>

                    <div class="border-b border-dashed border-black pb-2 mb-3 space-y-0.5">
                        <div class="flex justify-between">
                            <span>Receipt:</span>
                            <span class="font-bold">{{ $receiptData['invoice_number'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date:</span>
                            <span>{{ $receiptData['created_at'] }}</span>
                        </div>
                    </div>

                    <div class="border-b border-dashed border-black pb-3 mb-3 space-y-1.5">
                        @foreach($receiptData['items'] as $item)
                            <div class="flex justify-between font-bold">
                                <span>{{ $item['name'] }}</span>
                                <span>€{{ number_format($item['total'], 2) }}</span>
                            </div>
                            @if(!empty($item['description']))
                                <div class="text-[10px] text-slate-700 italic pl-2">
                                    Note: {{ $item['description'] }}
                                </div>
                            @endif
                            <div class="text-[10px] text-slate-600 pl-2">
                                {{ $item['quantity'] }} @ €{{ number_format($item['price'], 2) }}
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-1 pt-1">
                        <div class="flex justify-between">
                            <span>Taxable Subtotal:</span>
                            <span>€{{ number_format($receiptData['subtotal'], 2) }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-sm border-t border-black pt-2 mt-2">
                            <span>GRAND TOTAL:</span>
                            <span>€{{ number_format($receiptData['grand_total'], 2) }}</span>
                        </div>
                        @if(isset($receiptData['change_due']) && $receiptData['change_due'] > 0)
                            <div class="flex justify-between text-xs pt-1.5 border-t border-dotted border-black mt-1">
                                <span>TENDERED CASH:</span>
                                <span>€{{ number_format($receiptData['total_paid'], 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-xs pt-1">
                                <span>CHANGE RETURNED:</span>
                                <span>€{{ number_format($receiptData['change_due'], 2) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-center pt-6 text-[11px]">
                        <p class="font-bold">Thank you for your purchase!</p>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-200 flex gap-3 print:hidden">
                    <button 
                        onclick="window.print()" 
                        class="flex-1 py-2.5 rounded-sm bg-[#0a77c0] hover:bg-[#2980b9] text-white font-bold text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Print Receipt</span>
                    </button>
                    <button 
                        wire:click="closeReceiptModal" 
                        class="px-4 py-2.5 rounded-sm bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm transition cursor-pointer"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Review Sale Modal Component -->
    <livewire:pos.checkout-modal wire:key="cash-register-checkout-modal-v1" />

    <!-- Edit Cart Item Modal Component -->
    <livewire:pos.cart-item-modal wire:key="cash-register-cart-item-modal-v1" />

    <!-- Create Customer Modal -->
    @if($showCustomerCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-300 rounded-xs shadow-xl w-full max-w-md overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-[#E2E8F0] border-b border-slate-300 px-4 py-3 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-base">Create New Customer</h3>
                    <button wire:click="$set('showCustomerCreateModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="createCustomer" class="p-4 space-y-3.5 text-sm">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-slate-300 rounded-xs focus:outline-none focus:border-slate-500" placeholder="e.g. paul tighe" />
                        @error('name') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-slate-300 rounded-xs focus:outline-none focus:border-slate-500" placeholder="e.g. 0872504432" />
                        @error('phone') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" wire:model="email" class="w-full px-3 py-2 border border-slate-300 rounded-xs focus:outline-none focus:border-slate-500" placeholder="e.g. paul.tighe@gmail.com" />
                        @error('email') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Company</label>
                            <input type="text" wire:model="company" class="w-full px-3 py-2 border border-slate-300 rounded-xs focus:outline-none focus:border-slate-500" placeholder="e.g. Collins Repairs" />
                            @error('company') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Landline</label>
                            <input type="text" wire:model="landline" class="w-full px-3 py-2 border border-slate-300 rounded-xs focus:outline-none focus:border-slate-500" placeholder="e.g. 0656822234" />
                            @error('landline') <span class="text-red-500 text-xs font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="$set('showCustomerCreateModal', false)" 
                            class="px-4 py-2 border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold rounded-xs cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold rounded-xs cursor-pointer border border-yellow-500/40"
                        >
                            Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>