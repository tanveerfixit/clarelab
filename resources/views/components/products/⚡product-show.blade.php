<div class="bg-[#F4F7F9] font-sans p-4 md:p-6 min-h-screen text-slate-900">
    
    <!-- Toast Notification -->
    <div 
        x-data="{ show: false, message: '' }"
        x-on:show-toast.window="message = $event.detail.message; show = true; setTimeout(() => { show = false }, 4000)"
        x-show="show"
        x-cloak
        x-transition
        class="fixed top-20 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-md shadow-lg flex items-center gap-3 text-sm font-semibold border border-slate-700"
    >
        <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shrink-0">✓</div>
        <span x-text="message"></span>
        <button @click="show = false" class="text-slate-400 hover:text-white font-bold ml-2 text-xs">✕</button>
    </div>

    <!-- Main Header Card (Thumbnail, Product Title, SKU, Type, Selling Price, Manage & Products List) -->
    <div class="bg-white border border-slate-300 rounded-sm p-6 mb-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        
        <!-- Left Product Identity -->
        <div class="flex items-start gap-4">
            <!-- Thumbnail Graphic Box -->
            <div class="w-16 h-16 bg-slate-100 border border-slate-300 rounded-sm flex items-center justify-center text-slate-600 shrink-0 shadow-2xs">
                <svg class="w-8 h-8 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>

            <!-- Title & Metadata -->
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-2 text-sm text-slate-700 font-mono">
                    <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded border border-slate-300 text-slate-900 font-bold">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>{{ $product->sku }}</span>
                    </span>
                    <span class="font-sans font-bold text-slate-800">{{ $product->manufacturer ?? $product->category_name ?? 'General' }}</span>
                </div>

                <div class="text-sm text-slate-600 font-normal pt-1">
                    <span>Inventory & Tracking Type : </span>
                    <span class="font-bold text-slate-900 capitalize text-sm">{{ $product->type ?? 'Standard' }}</span>
                </div>
            </div>
        </div>

        <!-- Right Price & Header Actions -->
        <div class="flex flex-col items-end gap-3 shrink-0">
            <div class="text-right">
                <span class="text-xs uppercase tracking-wider font-bold text-slate-500 block">SELLING PRICE</span>
                <span class="text-3xl font-extrabold text-slate-900 font-mono">€{{ number_format($product->selling_price, 2) }}</span>
            </div>

            <div class="flex items-center gap-2 relative" x-data="{ open: false }">
                <!-- Manage Dropdown Button -->
                <button 
                    @click="open = !open"
                    class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 font-bold text-sm rounded-sm border border-slate-300 shadow-2xs flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Manage</span>
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <!-- Dropdown Menu -->
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-cloak
                    x-transition
                    class="absolute right-32 top-11 w-48 bg-white border border-slate-200 rounded-sm shadow-xl py-1.5 z-30 text-sm font-sans"
                >
                    <a href="/products/{{ $product->id }}/edit" wire:navigate class="block px-4 py-2 text-slate-800 hover:bg-slate-50 font-bold">Edit Product</a>
                    <button wire:click="archiveProduct" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 font-bold cursor-pointer">Archive Product</button>
                </div>

                <!-- Products List Button -->
                <a 
                    href="/products" 
                    wire:navigate 
                    class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-800 font-bold text-sm rounded-sm border border-slate-300 shadow-2xs flex items-center gap-2"
                >
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    <span>Products List</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs Bar -->
    <div class="mb-5 border-b border-slate-300 flex items-center gap-1.5">
        <button 
            wire:click="setTab('info')"
            class="px-5 py-2.5 text-sm font-bold rounded-t-sm border border-b-0 transition cursor-pointer {{ $activeTab === 'info' ? 'bg-white text-slate-900 border-slate-300 shadow-2xs' : 'bg-slate-200/70 text-slate-600 border-transparent hover:bg-slate-200' }}"
        >
            Product Information
        </button>

        <button 
            wire:click="setTab('pricing')"
            class="px-5 py-2.5 text-sm font-bold rounded-t-sm border border-b-0 transition cursor-pointer {{ $activeTab === 'pricing' ? 'bg-white text-slate-900 border-slate-300 shadow-2xs' : 'bg-slate-200/70 text-slate-600 border-transparent hover:bg-slate-200' }}"
        >
            Special Pricing
        </button>

        <button 
            wire:click="setTab('activity')"
            class="px-5 py-2.5 text-sm font-bold rounded-t-sm border border-b-0 transition cursor-pointer {{ $activeTab === 'activity' ? 'bg-white text-slate-900 border-slate-300 shadow-2xs' : 'bg-slate-200/70 text-slate-600 border-transparent hover:bg-slate-200' }}"
        >
            Activity Log
        </button>
    </div>

    <!-- Tab 1: Product Information Body (Full-Width Card) -->
    @if($activeTab === 'info')
        <div class="bg-white border border-slate-300 rounded-sm p-6 shadow-2xs space-y-6">
            
            <!-- Details List (Increased Font Sizes & Spacing) -->
            <div class="space-y-4 text-base">
                
                <!-- Need/Have/OnPO Stock Line + Bright Yellow Add Inventory Button -->
                <div class="flex flex-wrap items-center gap-4 py-1">
                    <span class="font-bold text-slate-900 text-base">Need/Have/OnPO :</span>
                    <a href="/products/{{ $product->id }}/edit" wire:navigate class="text-blue-600 hover:underline font-bold font-mono text-base">
                        {{ $product->need_qty ?? 0 }}/{{ $product->currentBranchStock() }}/{{ $product->on_po_qty ?? 0 }}
                    </a>
                    <button wire:click="toggleAddInventory" class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold text-sm rounded-xs transition shadow-2xs cursor-pointer border border-yellow-500/40 ml-2">
                        Add Inventory
                    </button>
                </div>

                @if($showAddInventory)
                    <div class="bg-white border border-slate-300 rounded-sm p-5 space-y-4 max-w-2xl my-3 shadow-2xs">
                        <!-- Supplier Row -->
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-1">Supplier Name<span class="text-red-500">*</span></label>
                            @if($showNewSupplierForm)
                                <div class="bg-slate-50 border border-slate-300 p-3 rounded-sm space-y-3 mb-2">
                                    <div class="text-xs font-bold text-slate-700 uppercase">Create New Supplier</div>
                                    <div class="space-y-2">
                                        <input type="text" wire:model="new_supplier_name" placeholder="Supplier Name*" class="w-full text-sm p-2 border border-slate-300 rounded-sm bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        @error('new_supplier_name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                                        
                                        <input type="email" wire:model="new_supplier_email" placeholder="Email" class="w-full text-sm p-2 border border-slate-300 rounded-sm bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <input type="text" wire:model="new_supplier_phone" placeholder="Phone" class="w-full text-sm p-2 border border-slate-300 rounded-sm bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="saveNewSupplier" class="px-3 py-1.5 bg-[#10B981] hover:bg-[#059669] text-white font-bold text-xs rounded-sm transition cursor-pointer">Save Supplier</button>
                                        <button type="button" wire:click="toggleNewSupplier" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-sm transition cursor-pointer">Cancel</button>
                                    </div>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <select wire:model="supplier_id" class="flex-1 text-sm p-2 border border-slate-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                                        <option value="">Select Supplier</option>
                                        @foreach($this->suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" wire:click="toggleNewSupplier" class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-sm border border-slate-300 transition cursor-pointer">
                                        + New
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Cost Input -->
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-1">Cost<span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model.live="cost" class="w-full text-sm p-2 border border-slate-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('cost') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Quantity Row -->
                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-1">QTY<span class="text-red-500">*</span></label>
                            <input type="number" wire:model.live="qty" {{ ($product->has_serial || $product->type === 'serialized') ? 'disabled' : '' }} class="w-full text-sm p-2 border border-slate-300 rounded-sm bg-slate-100 disabled:text-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            @error('qty') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-right text-sm font-bold text-slate-900 font-mono">
                            Total : €{{ number_format((float)$cost * $qty, 2) }}
                        </div>

                        <!-- Serial Numbers Textarea -->
                        @if($product->has_serial || $product->type === 'serialized')
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-1">Serial Numbers<span class="text-red-500">*</span></label>
                                <textarea wire:model.live="serial_numbers" rows="3" placeholder="One Serial number per line" class="w-full text-sm p-2 border border-slate-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono"></textarea>
                                @error('serial_numbers') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Print Barcode -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-slate-800">Print Barcode</span>
                            <label class="inline-flex items-center gap-1.5 cursor-pointer text-sm">
                                <input type="checkbox" wire:model="print_barcode" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="font-semibold text-slate-700">Yes</span>
                            </label>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" wire:click="toggleAddInventory" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-sm rounded-sm border border-slate-300 transition cursor-pointer">Cancel</button>
                            <button type="button" wire:click="addInventory" class="px-4 py-2 bg-[#10B981] hover:bg-[#059669] text-white font-bold text-sm rounded-sm transition cursor-pointer">Add</button>
                        </div>
                    </div>
                @endif

                <!-- Minimum Stock -->
                <div class="flex items-center gap-3 py-0.5">
                    <span class="font-bold text-slate-900 text-base">Minimum Stock :</span>
                    <span class="font-mono text-slate-900 text-base font-semibold">{{ $product->min_stock_level ?? 0 }}</span>
                </div>

                <!-- Selling Price -->
                <div class="flex items-center gap-3 py-0.5">
                    <span class="font-bold text-slate-900 text-base">Selling Price :</span>
                    <span class="font-mono text-slate-900 font-bold text-base">€{{ number_format($product->selling_price, 2) }}</span>
                </div>

                <!-- Minimum Selling Price -->
                <div class="flex items-center gap-3 py-0.5">
                    <span class="font-bold text-slate-900 text-base">Minimum Selling Price :</span>
                    <span class="font-mono text-slate-900 text-base">€{{ number_format($product->min_sales_price ?? 0.00, 2) }}</span>
                </div>

                <!-- Taxable -->
                <div class="flex items-center gap-3 py-0.5">
                    <span class="font-bold text-slate-900 text-base">Taxable :</span>
                    <span class="text-slate-900 font-semibold text-base">{{ $product->is_taxable ? 'Yes' : 'No' }}</span>
                </div>

                <!-- Additional Attributes -->
                @if($product->color)
                    <div class="flex items-center gap-3 py-0.5">
                        <span class="font-bold text-slate-900 text-base">Color :</span>
                        <span class="text-slate-900 text-base">{{ $product->color }}</span>
                    </div>
                @endif

                @if($product->condition)
                    <div class="flex items-center gap-3 py-0.5">
                        <span class="font-bold text-slate-900 text-base">Condition :</span>
                        <span class="text-slate-900 text-base">Grade {{ $product->condition }}</span>
                    </div>
                @endif

                @if($product->storage)
                    <div class="flex items-center gap-3 py-0.5">
                        <span class="font-bold text-slate-900 text-base">Storage :</span>
                        <span class="text-slate-900 text-base">{{ $product->storage }}</span>
                    </div>
                @endif
            </div>

            <!-- Serial Numbers (IMEIs) List for Serialized Products -->
            @if($product->has_serial || $product->type === 'serialized')
                @php
                    $branchSerials = $product->serials()
                        ->where('branch_id', session('active_branch_id', 1))
                        ->where('status', 'available')
                        ->get();
                @endphp
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <span class="font-bold text-slate-900 text-base block mb-2">In-Stock Serial Numbers (IMEIs) at this Branch:</span>
                    @if($branchSerials->isEmpty())
                        <span class="text-slate-500 text-sm italic">No serial numbers currently in stock for this branch.</span>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($branchSerials as $serial)
                                <div class="bg-slate-50 border border-slate-200 rounded-sm px-3 py-1.5 text-sm font-mono text-slate-800 flex items-center justify-between">
                                    <span>{{ $serial->serial_number }}</span>
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500" title="Available"></span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <!-- Bottom Action Buttons: Blue Edit & Red Archive (Touch-Friendly Sizes) -->
            <div class="pt-6 border-t border-slate-200 flex items-center gap-3">
                <a 
                    href="/products/{{ $product->id }}/edit" 
                    wire:navigate 
                    class="px-6 py-2.5 bg-[#0070BA] hover:bg-[#005B96] text-white font-bold text-sm rounded-sm transition shadow-2xs flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit</span>
                </a>

                <button 
                    wire:click="archiveProduct"
                    wire:confirm="Are you sure you want to archive this product?"
                    class="px-6 py-2.5 bg-[#DC3545] hover:bg-[#C82333] text-white font-bold text-sm rounded-sm transition shadow-2xs flex items-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Archive</span>
                </button>
            </div>
        </div>
    @elseif($activeTab === 'pricing')
        <div class="bg-white border border-slate-300 rounded-sm p-6 shadow-2xs text-base">
            <h3 class="font-bold text-slate-900 mb-2 text-lg">Special & Tier Pricing</h3>
            <p class="text-sm text-slate-500 mb-4">Configure wholesale or customer group pricing tiers for {{ $product->name }}.</p>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700 font-medium">
                Standard customer pricing applies (€{{ number_format($product->selling_price, 2) }}). No special tier pricing overrides configured.
            </div>
        </div>
    @elseif($activeTab === 'activity')
        <div class="bg-white border border-slate-300 rounded-sm p-6 shadow-2xs text-base">
            <h3 class="font-bold text-slate-900 mb-2 text-lg">Product Audit Log</h3>
            <div class="space-y-3 pt-2 text-sm">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded flex justify-between items-center">
                    <div>
                        <span class="font-bold text-slate-900 block text-base">Product Created</span>
                        <span class="text-slate-500 text-xs">Initial registration in catalog</span>
                    </div>
                    <span class="font-mono text-slate-600 font-semibold">{{ $product->created_at ? $product->created_at->format('d-m-Y g:i a') : 'Now' }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
