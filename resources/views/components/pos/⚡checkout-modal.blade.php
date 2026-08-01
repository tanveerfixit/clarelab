<div x-data="{ open: @entangle('isOpen') }" x-show="open" class="relative z-50" style="display: none;">
    <!-- Modal Backdrop -->
    <div 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 p-4 transition-opacity duration-200"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <!-- REVIEW SALE Modal Box -->
        <div @click.away="$wire.close()" class="bg-white rounded-sm shadow-2xl w-full max-w-md overflow-hidden border border-slate-300 flex flex-col font-sans">
            
            <!-- Modal Header: Light Gray Header Bar -->
            <div class="px-5 py-3 bg-[#E2E8F0] border-b border-slate-300 flex justify-between items-center text-slate-800">
                <span class="font-extrabold text-sm uppercase tracking-wider">REVIEW SALE</span>
                <button wire:click="close" class="text-slate-500 hover:text-slate-900 font-bold text-lg cursor-pointer">✕</button>
            </div>

            <!-- Modal Content Body -->
            <div class="p-5 space-y-4">

                @if($modalError)
                    <div class="bg-red-50 border border-red-200 rounded-sm p-3 text-xs text-red-600 font-bold flex justify-between items-center">
                        <span>{{ $modalError }}</span>
                        <button wire:click="$set('modalError', null)" class="font-bold">✕</button>
                    </div>
                @endif

                <!-- Top Block: Total Amount Card -->
                <div class="bg-slate-50 border border-slate-200 rounded-sm p-5 text-center shadow-2xs">
                    <span class="text-xs uppercase font-extrabold text-slate-500 tracking-wider block">TOTAL AMOUNT</span>
                    <div class="text-4xl font-extrabold text-slate-900 font-mono mt-1">€{{ number_format($grandTotalAmount, 2) }}</div>
                </div>

                <!-- Middle Block: Payment Details (Supporting Single or Split Payments) -->
                <div class="space-y-2">
                    <div class="text-xs uppercase font-extrabold text-slate-600 border-b border-slate-200 pb-1 tracking-wider">
                        PAYMENT DETAILS
                    </div>
                    
                    <div class="divide-y divide-slate-100">
                        @foreach($appliedPayments as $p)
                            <div class="flex justify-between items-center text-sm py-1.5 font-mono">
                                <span class="font-bold text-slate-800">{{ $p['method'] }}</span>
                                <span class="font-extrabold text-slate-900 text-base">€{{ number_format($p['amount'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($changeDue > 0)
                        <div class="flex justify-between items-center text-sm py-2 px-3 bg-emerald-50 border border-emerald-100 rounded-sm font-sans mt-2">
                            <span class="font-bold text-emerald-800">CHANGE DUE:</span>
                            <span class="font-extrabold text-emerald-700 text-lg font-mono">€{{ number_format($changeDue, 2) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Lower Block: Receipt Style Toggle -->
                <div class="space-y-2">
                    <div class="text-xs uppercase font-extrabold text-slate-600 tracking-wider">
                        RECEIPT STYLE
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            wire:click="selectReceiptStyle('THERMAL')"
                            class="py-3 px-4 rounded-sm border font-bold text-sm transition flex items-center justify-center gap-2 cursor-pointer {{ $receiptStyle === 'THERMAL' ? 'bg-slate-900 text-white border-slate-900 shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-300' }}"
                        >
                            <span>🖨️</span>
                            <span>THERMAL</span>
                        </button>

                        <button 
                            wire:click="selectReceiptStyle('A4')"
                            class="py-3 px-4 rounded-sm border font-bold text-sm transition flex items-center justify-center gap-2 cursor-pointer {{ $receiptStyle === 'A4' ? 'bg-slate-900 text-white border-slate-900 shadow-2xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-300' }}"
                        >
                            <span>📄</span>
                            <span>A4</span>
                        </button>
                    </div>
                </div>

                <!-- Modal Footer Actions -->
                <div class="flex justify-end gap-2.5 pt-2 border-t border-slate-200">
                    <button 
                        wire:click="close"
                        class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 font-bold text-sm rounded-sm transition cursor-pointer shadow-2xs"
                    >
                        Cancel
                    </button>

                    <button 
                        wire:click="finalizeSale"
                        class="px-6 py-2.5 bg-[#FFB300] hover:bg-amber-500 text-slate-900 font-extrabold text-sm rounded-sm transition cursor-pointer shadow-2xs flex items-center gap-1.5"
                    >
                        <span>✓</span>
                        <span>Finalize</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
