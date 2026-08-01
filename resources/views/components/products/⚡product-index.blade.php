<div class="bg-[#F4F7F9] font-sans p-4 min-h-screen text-slate-900">
    
    <!-- Modern Toast Notification -->
    <div 
        x-data="{ show: false, message: '' }"
        x-on:show-toast.window="message = $event.detail.message; show = true; setTimeout(() => { show = false }, 4000)"
        x-show="show"
        x-cloak
        x-transition
        class="fixed top-20 right-6 z-50 bg-slate-900 text-white px-5 py-3 rounded-md shadow-lg flex items-center gap-3 text-base font-semibold border border-slate-700"
    >
        <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-extrabold shrink-0">✓</div>
        <span x-text="message"></span>
        <button @click="show = false" class="text-slate-400 hover:text-white font-bold ml-2 text-xs">✕</button>
    </div>

    <!-- Header Bar: Manage Products Title + Yellow Create Product Button -->
    <div class="flex items-center justify-between pb-3.5 mb-2">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manage Products</h1>

        <!-- Yellow + Create Product Button -->
        <a 
            href="/products/create"
            wire:navigate
            class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold text-sm rounded-xs transition shadow-2xs flex items-center gap-2 cursor-pointer border border-yellow-500/40"
        >
            <span class="font-extrabold text-base">+</span>
            <span>Create Product</span>
        </a>
    </div>

    <!-- Filter Toolbar: All Products | All Manufacturers | All Categories | Search Products -->
    <div class="bg-white border border-slate-300 rounded-xs p-3 shadow-2xs mb-3 flex flex-wrap items-center justify-between gap-3 text-sm">
        
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- 1. All Products Dropdown -->
            <select 
                wire:model.live="selectedType"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[160px] cursor-pointer"
            >
                <option value="all">All Products</option>
                <option value="standard">Standard Products</option>
                <option value="serialized">Serialized Products</option>
                <option value="variable">Variable Products</option>
                <option value="service">Service / Repairs</option>
            </select>

            <!-- 2. All Manufacturers Dropdown -->
            <select 
                wire:model.live="selectedManufacturer"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[180px] cursor-pointer"
            >
                <option value="all">All Manufacturers</option>
                @foreach($manufacturers as $mfg)
                    <option value="{{ $mfg }}">{{ $mfg }}</option>
                @endforeach
            </select>

            <!-- 3. All Categories Dropdown -->
            <select 
                wire:model.live="selectedCategory"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[180px] cursor-pointer"
            >
                <option value="all">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <!-- 4. Search Products Input + Magnifying Search Button -->
        <div class="flex items-center gap-0 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search"
                placeholder="Search Products" 
                class="px-3.5 py-2 bg-white border border-slate-300 rounded-l-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans w-64"
            />
            <button class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 border border-l-0 border-slate-300 rounded-r-xs cursor-pointer flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Products Data Grid Table (Matches Reference Screenshot Compact Cell Padding) -->
    <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-base">
                <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-bold text-sm">
                    <tr>
                        <th class="py-1.5 px-2.5 border-r border-slate-300 w-1/6 cursor-pointer hover:bg-slate-300/60" wire:click="sortBy('manufacturer')">
                            Manufacturer Name @if($sortField === 'manufacturer') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="py-1.5 px-2.5 border-r border-slate-300 w-2/5 cursor-pointer hover:bg-slate-300/60" wire:click="sortBy('name')">
                            Product Name @if($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="py-1.5 px-2.5 border-r border-slate-300 text-center w-36 cursor-pointer hover:bg-slate-300/60" wire:click="sortBy('sku')">
                            SKU/Barcode @if($sortField === 'sku') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="py-1.5 px-2.5 border-r border-slate-300 w-48 cursor-pointer hover:bg-slate-300/60" wire:click="sortBy('category_name')">
                            Category Name @if($sortField === 'category_name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="py-1.5 px-2.5 border-r border-slate-300 text-right w-32 cursor-pointer hover:bg-slate-300/60" wire:click="sortBy('selling_price')">
                            Selling Price @if($sortField === 'selling_price') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="py-1.5 px-2.5 text-center w-40">
                            Need/Have/OnPO
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-800 text-base font-sans">
                    @forelse($products as $product)
                        <tr wire:key="product-row-{{ $product->id }}" class="hover:bg-amber-50/60 transition-colors group">
                            <!-- 1. Manufacturer Name -->
                            <td class="py-1 px-2.5 border-r border-slate-200 text-slate-800 font-normal">
                                {{ $product->manufacturer ?? '' }}
                            </td>

                            <!-- 2. Product Name (Clickable link to Product Detail Page) -->
                            <td class="py-1 px-2.5 border-r border-slate-200 font-normal text-slate-900">
                                <a href="/products/{{ $product->id }}" wire:navigate class="text-blue-600 hover:text-blue-800 hover:underline font-semibold flex items-center justify-between">
                                    <span>{{ $product->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-blue-600 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>

                            <!-- 3. SKU / Barcode -->
                            <td class="py-1 px-2.5 border-r border-slate-200 text-slate-800 font-mono text-center text-sm">
                                {{ $product->sku }}
                            </td>

                            <!-- 4. Category Name -->
                            <td class="py-1 px-2.5 border-r border-slate-200 text-slate-800 font-normal">
                                <div class="flex items-center justify-between">
                                    <span>{{ $product->category_name ?? '' }}</span>
                                    @if(str_contains(strtolower($product->category_name ?? ''), 'trade-in'))
                                        <span class="bg-slate-500 text-white text-xs px-1 py-0 rounded-xs">...</span>
                                    @endif
                                </div>
                            </td>

                            <!-- 5. Selling Price -->
                            <td class="py-1 px-2.5 border-r border-slate-200 text-right font-mono text-slate-900">
                                €{{ number_format($product->selling_price, 2) }}
                            </td>

                            <!-- 6. Need / Have / OnPO Status Column (Clickable to Detail Page) -->
                            <td class="py-1 px-2.5 text-center">
                                @php
                                    $hasNegativeStock = $product->stock_quantity < 0;
                                @endphp

                                <a href="/products/{{ $product->id }}" wire:navigate class="inline-flex items-center gap-1 px-1.5 py-0 rounded-xs {{ $hasNegativeStock ? 'bg-pink-100 text-blue-700 font-bold border border-pink-200' : 'text-blue-600' }}">
                                    <span class="hover:underline text-sm">
                                        {{ $product->need_qty }}/{{ $product->stock_quantity }}/{{ $product->on_po_qty }}
                                    </span>
                                    <svg class="w-3.5 h-3.5 text-blue-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 210.3H3v-3.572L16.732 3.732z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-base">
                                No products found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-3.5 border-t border-slate-200 bg-slate-50 text-sm">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Create Product Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xs shadow-2xl max-w-lg w-full overflow-hidden text-sm">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-base text-slate-900">Create Product</h3>
                    <button wire:click="closeAddModal" class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                </div>

                <form wire:submit.prevent="saveProduct" class="p-5 space-y-4">
                    <div class="space-y-1">
                        <label for="modal-product-name" class="font-bold text-slate-800 text-sm">Product Name <span class="text-red-500">*</span></label>
                        <input 
                            id="modal-product-name"
                            name="modal_product_name"
                            type="text" 
                            wire:model="name"
                            placeholder="e.g. 16GB USB Stick"
                            class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900"
                        />
                        @error('name') <span class="text-red-500 font-semibold text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="modal-manufacturer" class="font-bold text-slate-800 text-sm">Manufacturer</label>
                            <input 
                                id="modal-manufacturer"
                                name="modal_manufacturer"
                                type="text" 
                                wire:model="manufacturer"
                                placeholder="e.g. Apple, Nokia"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="modal-category-name" class="font-bold text-slate-800 text-sm">Category Name</label>
                            <input 
                                id="modal-category-name"
                                name="modal_category_name"
                                type="text" 
                                wire:model="category_name"
                                placeholder="e.g. Accessories, Labour"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label for="modal-product-type" class="font-bold text-slate-800 text-sm">Product Type</label>
                            <select 
                                id="modal-product-type"
                                name="modal_product_type"
                                wire:model.live="type"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900"
                            >
                                <option value="standard">Standard</option>
                                <option value="serialized">Serialized (IMEI)</option>
                                <option value="variable">Variable</option>
                                <option value="service">Service / Repair</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="modal-sku" class="font-bold text-slate-800 text-sm">SKU / Barcode</label>
                            <input 
                                id="modal-sku"
                                name="modal_sku"
                                type="text" 
                                wire:model="sku"
                                placeholder="e.g. 1391385"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900 font-mono"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label for="modal-cost-price" class="font-bold text-slate-800 text-sm">Cost Price (€)</label>
                            <input 
                                id="modal-cost-price"
                                name="modal_cost_price"
                                type="number" 
                                step="0.01"
                                wire:model="cost_price"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900 font-mono"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="modal-selling-price" class="font-bold text-slate-800 text-sm">Selling Price (€)</label>
                            <input 
                                id="modal-selling-price"
                                name="modal_selling_price"
                                type="number" 
                                step="0.01"
                                wire:model="selling_price"
                                placeholder="0.00"
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900 font-mono"
                            />
                        </div>

                        <div class="space-y-1">
                            <label for="modal-stock-qty" class="font-bold text-slate-800 text-sm">Initial Stock</label>
                            <input 
                                id="modal-stock-qty"
                                name="modal_stock_qty"
                                type="number" 
                                wire:model="stock_quantity"
                                placeholder="0"
                                {{ $type === 'service' ? 'disabled' : '' }}
                                class="w-full px-3.5 py-2 bg-slate-100 border-0 outline-none rounded-xs text-sm text-slate-900 font-mono disabled:opacity-50"
                            />
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end gap-2 border-t border-slate-200">
                        <button 
                            type="button" 
                            wire:click="closeAddModal"
                            class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xs transition cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold text-xs rounded-xs transition cursor-pointer border border-yellow-500/40"
                        >
                            Create Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
