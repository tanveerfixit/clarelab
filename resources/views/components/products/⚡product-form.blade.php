<div 
    x-data="{ 
        trackingMode: @entangle('form.inventory_tracking').live,
        hasSerial: @entangle('form.has_serial').live,
        accordionOpen: true
    }"
    class="bg-[#F4F7F9] font-sans p-4 md:p-6 min-h-screen text-slate-900"
>
    <!-- Top Bar Header with Actions (Clean un-boxed header without background container or border) -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="/products" wire:navigate class="p-1.5 rounded-sm bg-white hover:bg-slate-200 text-slate-600 transition border border-slate-300 shadow-2xs" title="Back to Products">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $isEditMode ? 'Edit Product' : 'Create Product' }}
                </h1>
                <p class="text-sm text-slate-500 font-normal mt-0.5">Configure product parameters, inventory tracking, pricing, and POS alerts</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <button 
                type="button" 
                wire:click="cancel"
                class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-bold text-sm rounded-sm border border-slate-300 transition cursor-pointer shadow-2xs"
            >
                Cancel
            </button>
            <button 
                type="button" 
                wire:click="save"
                class="px-5 py-2 bg-[#0070BA] hover:bg-[#005B96] text-white font-bold text-sm rounded-sm transition cursor-pointer shadow-2xs flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Product</span>
            </button>
        </div>
    </div>

    <!-- Main Responsive Form Wrap -->
    <form wire:submit.prevent="save" class="space-y-6 max-w-5xl">

        <!-- 1. SECTION: BASIC INFORMATION -->
        <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
            <div class="bg-[#F8FAFC] px-6 py-3.5 border-b border-slate-300 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0070BA]"></span>
                    <span>Basic Information</span>
                </h2>
                <span class="text-xs text-slate-400 font-normal">* Required fields</span>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Product Name -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="form-product-name" class="text-sm font-bold text-slate-800">Product Name <span class="text-red-500">*</span></label>
                        <input 
                            id="form-product-name"
                            name="form_product_name"
                            type="text" 
                            wire:model="form.product_name"
                            placeholder="eg. , Replace charging port, includes parts and labor"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] focus:ring-1 focus:ring-[#0070BA] transition font-sans placeholder:text-slate-400"
                        />
                        @error('form.product_name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Manufacturer / Brand -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="form-manufacturer-id" class="text-sm font-bold text-slate-800">Manufacturer / Brand</label>
                            <button 
                                type="button" 
                                wire:click="openNewManufacturerModal" 
                                class="text-xs text-[#0070BA] hover:underline font-semibold cursor-pointer"
                            >
                                + Add New Manufacturer
                            </button>
                        </div>
                        <select 
                            id="form-manufacturer-id"
                            name="form_manufacturer_id"
                            wire:model="form.manufacturer_id"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] cursor-pointer font-sans"
                        >
                            <option value="">Select Manufacturer Name</option>
                            @foreach($manufacturers as $mfg)
                                <option value="{{ $mfg }}">{{ $mfg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="form-category-id" class="text-sm font-bold text-slate-800">Category</label>
                            <button 
                                type="button" 
                                wire:click="openNewCategoryModal" 
                                class="text-xs text-[#0070BA] hover:underline font-semibold cursor-pointer"
                            >
                                + Add New Category
                            </button>
                        </div>
                        <select 
                            id="form-category-id"
                            name="form_category_id"
                            wire:model="form.category_id"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] cursor-pointer font-sans"
                        >
                            <option value="">Select Category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selling Price -->
                    <div class="space-y-1.5">
                        <label for="form-selling-price" class="text-sm font-bold text-slate-800">Selling Price (€) <span class="text-red-500">*</span></label>
                        <input 
                            id="form-selling-price"
                            name="form_selling_price"
                            type="number" 
                            step="0.01"
                            wire:model="form.selling_price"
                            placeholder="0.00"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 focus:outline-none focus:border-[#0070BA]"
                        />
                        <p class="text-xs text-slate-500 font-normal">The price the customer pays</p>
                        @error('form.selling_price') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- SKU / Barcode -->
                    <div class="space-y-1.5">
                        <label for="form-sku" class="text-sm font-bold text-slate-800">SKU / Barcode</label>
                        <input 
                            id="form-sku"
                            name="form_sku"
                            type="text" 
                            wire:model="form.sku"
                            placeholder="353010111108116"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 focus:outline-none focus:border-[#0070BA]"
                        />
                        <p class="text-xs text-slate-500 font-normal">(Optional) A unique code you use to identify this product</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. SECTION: INVENTORY & TRACKING -->
        <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
            <div class="bg-[#F8FAFC] px-6 py-3.5 border-b border-slate-300">
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0070BA]"></span>
                    <span>Inventory & Tracking</span>
                </h2>
                <p class="text-xs text-slate-500 font-normal mt-0.5">How will you track stock for this product?</p>
            </div>

            <div class="p-6 space-y-4">
                <!-- Vertical Stacked Radio Option Cards (All 4 Core Types + Bundles) -->
                <div class="space-y-3">
                    
                    <!-- Card 1: Standard (Basic Physical Inventory) -->
                    <label 
                        @click="trackingMode = 'standard'"
                        class="p-4 border rounded-sm cursor-pointer transition block relative"
                        :class="trackingMode === 'standard' ? 'border-[#3498DB] bg-[#EBF5FB] ring-1 ring-[#3498DB]' : 'border-slate-300 bg-white hover:border-slate-400'"
                    >
                        <div class="flex items-start gap-3">
                            <input 
                                type="radio" 
                                name="inventory_tracking" 
                                value="standard" 
                                x-model="trackingMode"
                                class="mt-1 text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">Standard Product</span>
                                <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">Basic physical inventory (quantity-based) for items like cases, cables, and standard parts.</span>
                            </div>
                        </div>
                    </label>

                    <!-- Card 2: Serialized (High-Value Electronics IMEI/Serial) -->
                    <label 
                        @click="trackingMode = 'serialized'"
                        class="p-4 border rounded-sm cursor-pointer transition block relative"
                        :class="trackingMode === 'serialized' ? 'border-[#3498DB] bg-[#EBF5FB] ring-1 ring-[#3498DB]' : 'border-slate-300 bg-white hover:border-slate-400'"
                    >
                        <div class="flex items-start gap-3">
                            <input 
                                type="radio" 
                                name="inventory_tracking" 
                                value="serialized" 
                                x-model="trackingMode"
                                class="mt-1 text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div class="space-y-2 flex-1">
                                <div>
                                    <span class="font-bold text-sm text-slate-900 block">Serialized Product</span>
                                    <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">High-value electronics requiring individual IMEI / Serial number tracking per unit (Phones, Tablets, Consoles, Laptops).</span>
                                </div>

                                <!-- Nested Serialized Checkbox -->
                                <div x-show="trackingMode === 'serialized'" x-transition class="pt-2.5 border-t border-blue-200/80 mt-2">
                                    <label class="flex items-start gap-2.5 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            wire:model.live="form.has_serial"
                                            x-model="hasSerial"
                                            class="mt-0.5 rounded-sm text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                                        />
                                        <div>
                                            <span class="font-bold text-xs text-slate-900 block">This product has a Serial Number / IMEI / Unique ID on each piece</span>
                                            <span class="text-[11px] text-slate-500 block">Required for Phones, Tablets, Consoles, Laptops, Scooters.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Card 3: Variable (Matrix Products with Child SKUs) -->
                    <label 
                        @click="trackingMode = 'variable'"
                        class="p-4 border rounded-sm cursor-pointer transition block relative"
                        :class="trackingMode === 'variable' ? 'border-[#3498DB] bg-[#EBF5FB] ring-1 ring-[#3498DB]' : 'border-slate-300 bg-white hover:border-slate-400'"
                    >
                        <div class="flex items-start gap-3">
                            <input 
                                type="radio" 
                                name="inventory_tracking" 
                                value="variable" 
                                x-model="trackingMode"
                                class="mt-1 text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">Variable Product (Matrix)</span>
                                <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">Matrix products with child SKUs based on attributes (e.g., 64GB vs 128GB vs 256GB storage or color options).</span>
                            </div>
                        </div>
                    </label>

                    <!-- Card 4: Service (Non-Inventory Labor & Repairs) -->
                    <label 
                        @click="trackingMode = 'service'"
                        class="p-4 border rounded-sm cursor-pointer transition block relative"
                        :class="trackingMode === 'service' ? 'border-[#3498DB] bg-[#EBF5FB] ring-1 ring-[#3498DB]' : 'border-slate-300 bg-white hover:border-slate-400'"
                    >
                        <div class="flex items-start gap-3">
                            <input 
                                type="radio" 
                                name="inventory_tracking" 
                                value="service" 
                                x-model="trackingMode"
                                class="mt-1 text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">Service / Repair Labor</span>
                                <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">Non-inventory labor or repair tasks (infinite stock, bypassed inventory quantity checks).</span>
                            </div>
                        </div>
                    </label>

                    <!-- Card 5: Bundles (Package / Kit) -->
                    <label 
                        @click="trackingMode = 'bundles'"
                        class="p-4 border rounded-sm cursor-pointer transition block relative"
                        :class="trackingMode === 'bundles' ? 'border-[#3498DB] bg-[#EBF5FB] ring-1 ring-[#3498DB]' : 'border-slate-300 bg-white hover:border-slate-400'"
                    >
                        <div class="flex items-start gap-3">
                            <input 
                                type="radio" 
                                name="inventory_tracking" 
                                value="bundles" 
                                x-model="trackingMode"
                                class="mt-1 text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">Bundles</span>
                                <span class="text-xs text-slate-600 leading-relaxed block mt-0.5">Group multiple existing products or services to sell as a single package or kit.</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- 3. SECTION: PRODUCT DETAILS -->
        <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
            <div class="bg-[#F8FAFC] px-6 py-3.5 border-b border-slate-300">
                <h2 class="text-base font-bold text-slate-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0070BA]"></span>
                    <span>Product Details</span>
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                    
                    <div class="space-y-4">
                        <!-- Taxable Checkbox -->
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                wire:model="form.is_taxable"
                                class="mt-1 rounded-sm text-[#0070BA] focus:ring-[#0070BA] cursor-pointer"
                            />
                            <div>
                                <span class="font-bold text-sm text-slate-900 block">Taxable</span>
                                <span class="text-xs text-slate-500 leading-relaxed block">Check this box to apply your store's tax rate to this product at checkout.</span>
                            </div>
                        </label>

                        <!-- Minimum Stock Level -->
                        <div x-show="['standard', 'serialized', 'variable'].includes(trackingMode)" class="space-y-1.5 pt-2">
                            <label for="form-min-stock-level" class="text-sm font-bold text-slate-800 block">Minimum Stock Level</label>
                            <input 
                                id="form-min-stock-level"
                                name="form_min_stock_level"
                                type="number" 
                                wire:model="form.min_stock_level"
                                placeholder="0"
                                class="w-full md:w-56 px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 focus:outline-none focus:border-[#0070BA]"
                            />
                            <p class="text-xs text-slate-500 font-normal">Set the quantity that will trigger a low stock alert.</p>
                        </div>
                    </div>

                    <!-- Vibrant Cyan Callout Box for Serialized Products -->
                    <div x-show="trackingMode === 'serialized' || (['standard', 'variable'].includes(trackingMode) && hasSerial)" x-transition>
                        <div class="p-6 bg-[#1EB0E6] text-white rounded-sm shadow-2xs">
                            <p class="text-sm font-semibold leading-relaxed">
                                Serial numbers are added by Purchase Order or from the Product Information page after you save this new serialized product.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SECTION: ADDITIONAL DETAILS (COLLAPSIBLE ACCORDION) -->
        <div class="bg-white border border-slate-300 rounded-sm shadow-2xs overflow-hidden">
            <button 
                type="button" 
                @click="accordionOpen = !accordionOpen"
                class="w-full px-6 py-4 flex items-center justify-between text-left bg-[#F8FAFC] hover:bg-slate-100 transition cursor-pointer border-b border-slate-300"
            >
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#0070BA]"></span>
                    <h2 class="text-base font-bold text-slate-900 uppercase tracking-tight">Additional Details</h2>
                </div>
                <span class="font-bold text-base text-slate-600" x-text="accordionOpen ? '▲' : '▼'">▲</span>
            </button>

            <div x-show="accordionOpen" x-transition class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Minimum Sales Price -->
                    <div class="space-y-1.5">
                        <label for="form-min-sales-price" class="text-sm font-bold text-slate-800">Minimum Sales Price (€)</label>
                        <input 
                            id="form-min-sales-price"
                            name="form_min_sales_price"
                            type="number" 
                            step="0.01"
                            wire:model="form.min_sales_price"
                            placeholder="0.00"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm font-mono text-slate-900 focus:outline-none focus:border-[#0070BA]"
                        />
                        <p class="text-xs text-slate-500 font-normal">Prevents staff from selling bellow this price without override</p>
                    </div>

                    <!-- Color -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="form-color" class="text-sm font-bold text-slate-800">Color</label>
                            <button 
                                type="button" 
                                wire:click="openNewColorModal" 
                                class="text-xs text-[#0070BA] hover:underline font-semibold cursor-pointer"
                            >
                                + Add New Color
                            </button>
                        </div>
                        <select 
                            id="form-color"
                            name="form_color"
                            wire:model="form.color"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] cursor-pointer font-sans"
                        >
                            <option value="">-</option>
                            @foreach($colors as $clr)
                                <option value="{{ $clr }}">{{ $clr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Physical Condition -->
                    <div class="space-y-1.5">
                        <label for="form-condition" class="text-sm font-bold text-slate-800">Physical Condition</label>
                        <select 
                            id="form-condition"
                            name="form_condition"
                            wire:model="form.condition"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] cursor-pointer font-sans"
                        >
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="Refurbished">Refurbished</option>
                        </select>
                    </div>

                    <!-- Storage -->
                    <div class="space-y-1.5">
                        <label for="form-storage" class="text-sm font-bold text-slate-800">Storage</label>
                        <input 
                            id="form-storage"
                            name="form_storage"
                            type="text" 
                            wire:model="form.storage"
                            placeholder="64GB"
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] font-sans"
                        />
                    </div>

                    <!-- Additional Customer Description -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="form-description" class="text-sm font-bold text-slate-800">Additional Description</label>
                        <textarea 
                            id="form-description"
                            name="form_description"
                            wire:model="form.description"
                            rows="3"
                            placeholder="Customer visible notes..."
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] font-sans"
                        ></textarea>
                        <p class="text-xs text-slate-500 font-normal">This description is for your customers. It will be shown on your receipt (if enabled).</p>
                    </div>

                    <!-- POS Staff Alert Message -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="form-alert-message" class="text-sm font-bold text-slate-800">Alert Message</label>
                        <textarea 
                            id="form-alert-message"
                            name="form_alert_message"
                            wire:model="form.alert_message"
                            rows="2"
                            placeholder="Internal POS popup message for cashier..."
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:border-[#0070BA] font-sans"
                        ></textarea>
                        <p class="text-xs text-slate-500 font-normal">Add a short message that will pop up for your staff at the Point of Sale every time this product is added to the cart. This is perfect for upsell reminders, warnings, or special handling instructions.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. FORM FOOTER ACTIONS -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-300">
            <button 
                type="button" 
                wire:click="cancel"
                class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-bold text-sm rounded-sm border border-slate-300 transition cursor-pointer shadow-2xs"
            >
                Cancel
            </button>
            <button 
                type="submit"
                class="px-6 py-2.5 bg-[#0070BA] hover:bg-[#005B96] text-white font-bold text-sm rounded-sm transition cursor-pointer shadow-2xs flex items-center gap-1.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save Product</span>
            </button>
        </div>
    </form>

    <!-- Modal 1: Add New Manufacturer -->
    @if($showNewManufacturerModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-sm shadow-2xl max-w-md w-full overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-base text-slate-900">Add New Manufacturer</h3>
                    <button wire:click="$set('showNewManufacturerModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-1.5">
                        <label for="new-mfg-name" class="text-sm font-bold text-slate-800">Manufacturer Name</label>
                        <input 
                            id="new-mfg-name"
                            name="new_mfg_name"
                            type="text" 
                            wire:model="newManufacturerName" 
                            placeholder="e.g. Motorola, Anker"
                            class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-sm text-slate-900"
                        />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showNewManufacturerModal', false)" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-sm">Cancel</button>
                        <button type="button" wire:click="saveNewManufacturer" class="px-4 py-2 bg-[#0070BA] text-white font-bold text-xs rounded-sm">Add Manufacturer</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Add New Category -->
    @if($showNewCategoryModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-sm shadow-2xl max-w-md w-full overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-base text-slate-900">Add New Category</h3>
                    <button wire:click="$set('showNewCategoryModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-1.5">
                        <label for="new-cat-name" class="text-sm font-bold text-slate-800">Category Name</label>
                        <input 
                            id="new-cat-name"
                            name="new_cat_name"
                            type="text" 
                            wire:model="newCategoryName" 
                            placeholder="e.g. Laptop Repair, Cables"
                            class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-sm text-slate-900"
                        />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showNewCategoryModal', false)" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-sm">Cancel</button>
                        <button type="button" wire:click="saveNewCategory" class="px-4 py-2 bg-[#0070BA] text-white font-bold text-xs rounded-sm">Add Category</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 3: Add New Color -->
    @if($showNewColorModal)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-sm shadow-2xl max-w-md w-full overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-base text-slate-900">Add New Color</h3>
                    <button wire:click="$set('showNewColorModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm">✕</button>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-1.5">
                        <label for="new-color-name" class="text-sm font-bold text-slate-800">Color Name</label>
                        <input 
                            id="new-color-name"
                            name="new_color_name"
                            type="text" 
                            wire:model="newColorName" 
                            placeholder="e.g. Rose Gold, Coral"
                            class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-sm text-slate-900"
                        />
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showNewColorModal', false)" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-sm">Cancel</button>
                        <button type="button" wire:click="saveNewColor" class="px-4 py-2 bg-[#0070BA] text-white font-bold text-xs rounded-sm">Add Color</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
