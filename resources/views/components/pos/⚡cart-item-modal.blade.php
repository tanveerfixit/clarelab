<div x-data="{ open: @entangle('isOpen') }" x-show="open" class="relative z-50" style="display: none;">
    <!-- Modal Backdrop -->
    <div 
        class="fixed inset-0 bg-slate-900/50 flex items-center justify-center z-50 p-4 transition-opacity duration-200"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <!-- Modal Dialog Box (Matching exact reference styling) -->
        <div @click.away="$wire.close()" class="bg-white rounded-md shadow-2xl w-full max-w-xl overflow-hidden border border-slate-300 flex flex-col font-sans">
            
            <!-- Modal Header Bar (Light Gray Header) -->
            <div class="px-6 py-3.5 bg-[#EAECEE] border-b border-slate-300 flex justify-between items-center text-slate-800">
                <h3 class="font-bold text-xl text-slate-800 tracking-tight">Update POS Cart</h3>
                <button wire:click="close" class="text-slate-600 hover:text-slate-900 transition-colors cursor-pointer text-xl font-bold">
                    ✕
                </button>
            </div>

            <!-- Content Body (2-Column Form & Summary Alignment) -->
            <div class="p-7 space-y-6">
                
                <!-- Row 1: Unit Price -->
                <div class="grid grid-cols-12 items-center gap-4">
                    <label for="modal-unit-price" class="col-span-4 text-right font-bold text-slate-800 text-base">Unit Price:</label>
                    <div class="col-span-5">
                        <input 
                            id="modal-unit-price"
                            name="modal_unit_price"
                            type="number" 
                            step="0.01" 
                            wire:model.live.debounce.100ms="unit_price"
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-sm text-base text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono"
                        />
                    </div>
                    <div class="col-span-3 text-right font-bold text-slate-900 font-mono text-base">
                        €{{ number_format($this->unit_price ?: 0, 2) }}
                    </div>
                </div>

                <!-- Row 2: QTY -->
                <div class="grid grid-cols-12 items-center gap-4">
                    <label for="modal-quantity" class="col-span-4 text-right font-bold text-slate-800 text-base">QTY <span class="text-red-600 font-extrabold">*</span>:</label>
                    <div class="col-span-5">
                        <input 
                            id="modal-quantity"
                            name="modal_quantity"
                            type="number" 
                            step="0.01" 
                            wire:model.live.debounce.100ms="quantity"
                            required
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-sm text-base text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono"
                        />
                    </div>
                    <div class="col-span-3 text-right text-base text-slate-800">
                        Subtotal: <span class="font-bold text-slate-900 font-mono">€{{ number_format($this->subtotal, 2) }}</span>
                    </div>
                </div>

                <!-- Row 3: Discount & Addon Unit Selection -->
                <div class="grid grid-cols-12 items-center gap-4">
                    <label for="modal-discount-amount" class="col-span-4 text-right font-bold text-slate-800 text-base">Discount :</label>
                    <div class="col-span-5 flex">
                        <input 
                            id="modal-discount-amount"
                            name="modal_discount_amount"
                            type="number" 
                            step="0.01" 
                            wire:model.live.debounce.100ms="discount_amount"
                            placeholder="0.00"
                            class="w-full px-3 py-2 bg-white border border-r-0 border-slate-300 rounded-l-sm text-base text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono"
                        />
                        <label for="modal-discount-type" class="sr-only">Discount Type</label>
                        <select 
                            id="modal-discount-type"
                            name="modal_discount_type"
                            wire:model.live="discount_type"
                            class="bg-slate-200 border border-slate-300 rounded-r-sm px-2 text-sm font-bold text-slate-700 cursor-pointer focus:outline-none"
                        >
                            <option value="percentage">%</option>
                            <option value="fixed">€</option>
                        </select>
                    </div>
                    <div class="col-span-3 text-right font-bold text-slate-900 font-mono text-base">
                        -€{{ number_format($this->calculated_discount_value, 2) }}
                    </div>
                </div>

                <!-- Separator Divider Line -->
                <div class="border-t border-slate-200 pt-3">
                    <div class="text-right font-bold text-slate-900 text-lg">
                        Total: <span class="font-mono text-xl text-slate-900">€{{ number_format($this->total, 2) }}</span>
                    </div>
                </div>

                <!-- Row 4: Additional Description / Note -->
                <div class="grid grid-cols-12 items-start gap-4">
                    <label for="modal-description" class="col-span-4 text-right font-bold text-slate-800 text-base pt-2">Additional Description:</label>
                    <div class="col-span-8">
                        <textarea 
                            id="modal-description"
                            name="modal_description"
                            wire:model.live.debounce.150ms="description"
                            rows="3"
                            class="w-full p-3 bg-white border border-slate-300 rounded-sm text-base text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-500 font-sans"
                        ></textarea>
                    </div>
                </div>

            </div>

            <!-- Modal Footer Bar (Light Gray Footer) -->
            <div class="px-6 py-3.5 bg-[#EAECEE] border-t border-slate-300 flex justify-end gap-3">
                <button 
                    type="button"
                    wire:click="close"
                    class="px-5 py-2 bg-white hover:bg-slate-100 border border-slate-300 rounded-sm text-slate-800 text-base font-semibold cursor-pointer transition shadow-2xs"
                >
                    Cancel
                </button>
                <button 
                    type="button"
                    wire:click="save"
                    class="px-6 py-2 bg-[#007BFF] hover:bg-[#0056b3] text-white rounded-sm text-base font-bold cursor-pointer transition shadow-2xs"
                >
                    Save
                </button>
            </div>

        </div>
    </div>
</div>
