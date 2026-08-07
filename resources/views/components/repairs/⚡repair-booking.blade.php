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

    @if($activeTab === 'new_booking')
        <!-- Page Header for Book New Repair -->
        <div class="flex items-center justify-between pb-3.5 mb-4">
            <div class="flex items-center gap-3">
                <a 
                    href="/repairs" 
                    wire:navigate
                    class="px-3.5 py-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-sm rounded-xs transition shadow-2xs flex items-center gap-1.5 cursor-pointer"
                >
                    <span>←</span>
                    <span>Back to Repair Management</span>
                </a>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Book New Repair</h1>
            </div>
        </div>
    @endif

    @if($activeTab === 'new_booking')
        <!-- Main Container -->
        <div class="w-full flex flex-col lg:flex-row gap-5 items-start">

            <!-- 1. LEFT CONTAINER: Crisp Repair Booking Form Card -->
            <div class="flex-1 bg-white border border-slate-300 rounded-sm shadow-2xs p-6 space-y-5 w-full">
                
                <!-- Form Content -->
                <form wire:submit.prevent="saveBooking(false)" class="space-y-5">
                    
                    <!-- Customer Information Section -->
                    <div class="space-y-3">
                        <div class="text-sm font-extrabold uppercase tracking-wider text-slate-500 pb-1">1. Customer Information</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label for="repair-customer-name" class="text-sm font-bold text-slate-800">Customer Name <span class="text-red-500">*</span></label>
                                <input 
                                    id="repair-customer-name"
                                    name="repair_customer_name"
                                    type="text" 
                                    wire:model.live="customer_name" 
                                    placeholder="Full Name"
                                    class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-sans"
                                />
                                @error('customer_name') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="repair-phone-number" class="text-sm font-bold text-slate-800">Phone Number <span class="text-red-500">*</span></label>
                                <input 
                                    id="repair-phone-number"
                                    name="repair_phone_number"
                                    type="text" 
                                    wire:model.live="phone_number" 
                                    placeholder="08X XXX XXXX"
                                    class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-mono"
                                />
                                @error('phone_number') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label for="repair-email-address" class="text-sm font-bold text-slate-800">Email Address <span class="text-slate-400 font-normal">(Optional)</span></label>
                                <input 
                                    id="repair-email-address"
                                    name="repair_email_address"
                                    type="email" 
                                    wire:model.live="email_address" 
                                    placeholder="email@example.com"
                                    class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-sans"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Device & Fault Details Section -->
                    <div class="space-y-3 pt-2">
                        <div class="text-sm font-extrabold uppercase tracking-wider text-slate-500 pb-1">2. Device & Fault Details</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="space-y-1.5">
                                <label for="repair-device-model" class="text-sm font-bold text-slate-800">Device Model <span class="text-red-500">*</span></label>
                                <input 
                                    id="repair-device-model"
                                    name="repair_device_model"
                                    type="text" 
                                    wire:model.live="device_model" 
                                    placeholder="e.g. iPhone 13, Samsung S22"
                                    class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-sans"
                                />
                                @error('device_model') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label for="repair-problem-description" class="text-sm font-bold text-slate-800">Problem Description <span class="text-red-500">*</span></label>
                                <input 
                                    id="repair-problem-description"
                                    name="repair_problem_description"
                                    type="text" 
                                    wire:model.live="problem_description" 
                                    placeholder="Describe fault (e.g. Screen cracked, Battery health 75%)..."
                                    class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-sans"
                                />
                                @error('problem_description') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Part Order & Installation Section -->
                    <div class="space-y-3 pt-2">
                        <div class="text-sm font-extrabold uppercase tracking-wider text-slate-500 pb-1">3. Part Requirements <span class="text-slate-400 font-normal">(Optional)</span></div>
                        
                        <div class="space-y-1.5">
                            <label for="repair-part-needed" class="text-sm font-bold text-slate-800">Part to Need Order / Install <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <input 
                                id="repair-part-needed"
                                name="repair_part_needed"
                                type="text" 
                                wire:model.live="part_needed" 
                                placeholder="e.g. Original iPhone 13 OLED Display Screen, Genuine Samsung Battery, Charging Port Flex..."
                                class="w-full px-3.5 py-2.5 bg-slate-100 border-0 outline-none rounded-sm text-base font-normal text-slate-900 focus:outline-none focus:bg-slate-200 transition placeholder:text-slate-400 font-sans"
                            />
                        </div>
                    </div>

                    <!-- Quote & Financial Summary Bar -->
                    <div class="space-y-3 pt-2">
                        <div class="text-sm font-extrabold uppercase tracking-wider text-slate-500 pb-1">4. Payment & Quote Summary</div>

                        <div class="bg-slate-100/70 rounded-sm p-4 flex flex-col md:flex-row items-center justify-between gap-5">
                            
                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="space-y-1.5">
                                    <label for="repair-total-quote" class="text-sm font-bold text-slate-800 block">Total Quote (€)</label>
                                    <input 
                                        id="repair-total-quote"
                                        name="repair_total_quote"
                                        type="number" 
                                        step="0.01" 
                                        wire:model.live="total_quote" 
                                        placeholder="0.00"
                                        class="w-40 px-3.5 py-2.5 bg-white border-0 outline-none rounded-sm text-lg font-normal text-slate-900 focus:outline-none focus:bg-slate-200 font-mono"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <label for="repair-deposit-paid" class="text-sm font-bold text-slate-800 block">Deposit Paid (€)</label>
                                    <input 
                                        id="repair-deposit-paid"
                                        name="repair_deposit_paid"
                                        type="number" 
                                        step="0.01" 
                                        wire:model.live="deposit_paid" 
                                        placeholder="0.00"
                                        class="w-40 px-3.5 py-2.5 bg-white border-0 outline-none rounded-sm text-lg font-normal text-slate-900 focus:outline-none focus:bg-slate-200 font-mono"
                                    />
                                </div>
                            </div>

                            <div class="text-right border-t md:border-t-0 md:border-l border-slate-200 pt-3 md:pt-0 md:pl-6 w-full md:w-auto flex md:block justify-between items-center">
                                <span class="text-sm font-bold uppercase tracking-wider text-slate-600 block">Remaining Balance Due</span>
                                <span class="text-3xl font-extrabold text-slate-900 font-mono block mt-0.5">
                                    €{{ number_format($this->remainingBalance, 2) }}
                                </span>
                            </div>

                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-2 border-t border-slate-200">
                        <button 
                            type="submit"
                            class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-sm font-bold text-sm cursor-pointer transition text-center shadow-2xs"
                        >
                            Save Booking
                        </button>
                        
                        <button 
                            type="button"
                            wire:click="saveBooking(true)"
                            class="px-6 py-2.5 bg-[#0a77c0] hover:bg-[#08629f] text-white rounded-sm font-bold text-sm cursor-pointer transition text-center shadow-2xs flex items-center gap-2"
                        >
                            <span>Save & Print</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </button>
                    </div>

                </form>
            </div>

            <!-- 2. RIGHT CONTAINER: Compact Intake QR Card -->
            <div class="w-full lg:w-72 bg-white border border-slate-300 rounded-sm shadow-2xs p-5 flex flex-col items-center text-center space-y-4 shrink-0">
                
                <!-- Intake Title -->
                <div class="flex items-center justify-center gap-2 text-slate-800 font-bold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 text-[#0a77c0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Mobile Intake QR</span>
                </div>

                <!-- Active Scan Badge -->
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-50 border border-emerald-200 rounded-sm text-[11px] font-bold text-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Active Session</span>
                </div>

                <!-- Instruction Text -->
                <p class="text-xs text-slate-500 leading-normal font-sans">
                    Scan with phone camera to quickly submit repair info.
                </p>

                <!-- Clean QR Frame -->
                <div class="p-2 border border-slate-300 rounded-sm bg-white inline-block shadow-2xs">
                    <svg class="w-36 h-36 text-slate-900" viewBox="0 0 100 100" fill="currentColor">
                        <rect x="5" y="5" width="25" height="25" fill="none" stroke="currentColor" stroke-width="4"/>
                        <rect x="10" y="10" width="15" height="15" fill="currentColor"/>
                        <rect x="70" y="5" width="25" height="25" fill="none" stroke="currentColor" stroke-width="4"/>
                        <rect x="75" y="10" width="15" height="15" fill="currentColor"/>
                        <rect x="5" y="70" width="25" height="25" fill="none" stroke="currentColor" stroke-width="4"/>
                        <rect x="10" y="75" width="15" height="15" fill="currentColor"/>
                        
                        <rect x="35" y="5" width="8" height="8"/>
                        <rect x="50" y="5" width="12" height="8"/>
                        <rect x="35" y="18" width="12" height="8"/>
                        <rect x="52" y="18" width="10" height="8"/>
                        <rect x="5" y="35" width="8" height="12"/>
                        <rect x="18" y="35" width="12" height="8"/>
                        <rect x="5" y="52" width="12" height="10"/>
                        <rect x="35" y="35" width="15" height="15"/>
                        <rect x="55" y="35" width="10" height="8"/>
                        <rect x="70" y="35" width="10" height="12"/>
                        <rect x="85" y="35" width="10" height="8"/>
                        <rect x="35" y="55" width="8" height="10"/>
                        <rect x="48" y="55" width="14" height="14"/>
                        <rect x="68" y="55" width="10" height="10"/>
                        <rect x="83" y="50" width="12" height="12"/>
                        <rect x="35" y="75" width="12" height="18"/>
                        <rect x="52" y="75" width="18" height="10"/>
                        <rect x="75" y="72" width="20" height="8"/>
                        <rect x="75" y="85" width="10" height="10"/>
                        <rect x="88" y="85" width="8" height="10"/>
                    </svg>
                </div>

                <!-- Session ID Pill with Copy button -->
                <div 
                    x-data="{ copied: false }"
                    class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-sm text-xs font-semibold text-slate-700 flex items-center justify-center gap-2"
                >
                    <span>ID: <strong class="font-mono text-slate-900">{{ $session_id }}</strong></span>
                    <button 
                        @click="navigator.clipboard.writeText('{{ $session_id }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                        class="text-slate-400 hover:text-slate-800 cursor-pointer transition"
                        title="Copy Session ID"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <span x-show="copied" class="text-xs text-emerald-600 font-bold" style="display: none;">Copied!</span>
                </div>

                <!-- Check Submission Action Button -->
                <button 
                    wire:click="generateSessionId"
                    class="w-full py-2 bg-white hover:bg-slate-50 border border-slate-300 rounded-sm text-xs font-bold text-slate-800 cursor-pointer transition flex items-center justify-center gap-1.5 shadow-2xs"
                >
                    <span class="text-amber-500 font-bold">⚡</span>
                    <span>Check Submission</span>
                </button>

            </div>

        </div>
    @else
        <!-- OLD JOBS / REPAIR HISTORY TAB CONTENT -->
        <div class="w-full space-y-5">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-3 border-b border-slate-200 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Repair Management</h2>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">List of customer repair bookings</p>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <!-- Status Filter Dropdown -->
                    <select 
                        wire:model.live="statusFilter"
                        class="px-3 py-2 bg-white border border-slate-300 rounded-sm text-sm text-slate-800 focus:outline-none focus:ring-1 focus:ring-slate-900 font-sans cursor-pointer min-w-[150px]"
                    >
                        <option value="all">All Statuses</option>
                        <option value="Received">Booked</option>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">Processing</option>
                    </select>

                    <input 
                        type="text" 
                        wire:model.live.debounce.150ms="searchQuery"
                        placeholder="Search by Ticket #, Name, Phone, or Device..." 
                        class="px-3.5 py-2 bg-white border border-slate-300 rounded-sm text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 w-full md:w-80 font-sans"
                    />
                    <a 
                        href="/repairs/create"
                        wire:navigate
                        class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold text-sm rounded-sm transition shadow-2xs flex items-center gap-2 border border-yellow-500/40 whitespace-nowrap cursor-pointer"
                    >
                        <span class="font-extrabold text-base">+</span>
                        <span>Book New Repair</span>
                    </a>
                </div>
            </div>

            <!-- Crisp Clean History Table -->
            <div class="overflow-x-auto border border-slate-300 bg-white rounded-sm shadow-2xs">
                <table class="w-full text-left border-collapse text-base">
                    <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-800 font-extrabold uppercase tracking-tight text-sm">
                        <tr>
                            <th class="py-2.5 px-3 border-r border-slate-300">Ticket #</th>
                            <th class="py-2.5 px-3 border-r border-slate-300">Date</th>
                            <th class="py-2.5 px-3 border-r border-slate-300">Customer Name</th>
                            <th class="py-2.5 px-3 border-r border-slate-300">Phone</th>
                            <th class="py-2.5 px-3 border-r border-slate-300">Device Model</th>
                            <th class="py-2.5 px-3 border-r border-slate-300">Problem Description</th>
                            <th class="py-2.5 px-3 text-center border-r border-slate-300">Status</th>
                            <th class="py-2.5 px-3 text-right border-r border-slate-300">Balance</th>
                            <th class="py-2.5 px-3 text-center whitespace-nowrap">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-800 text-base">
                        @forelse($jobs as $job)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 px-3 font-mono font-bold text-slate-900 border-r border-slate-200 whitespace-nowrap">
                                    <a href="/repairs/{{ $job->ticket_number }}" wire:navigate class="text-[#0a77c0] hover:underline inline-flex items-center gap-1">
                                        <span>{{ $job->ticket_number }}</span>
                                    </a>
                                </td>
                                <td class="py-2.5 px-3 font-mono text-slate-600 border-r border-slate-200 whitespace-nowrap">{{ $job->created_at->format('d-m-Y H:i') }}</td>
                                <td class="py-2.5 px-3 font-bold text-slate-900 border-r border-slate-200 whitespace-nowrap">{{ $job->customer_name }}</td>
                                <td class="py-2.5 px-3 font-mono text-slate-600 border-r border-slate-200 whitespace-nowrap">{{ $job->phone_number }}</td>
                                <td class="py-2.5 px-3 font-semibold text-[#0a77c0] border-r border-slate-200 whitespace-nowrap">{{ $job->device_model }}</td>
                                <td class="py-2.5 px-3 text-slate-600 max-w-xs truncate border-r border-slate-200" title="{{ $job->problem_description }}">{{ $job->problem_description }}</td>
                                <td class="py-2.5 px-3 text-center border-r border-slate-200 whitespace-nowrap">
                                    @if($job->status === 'Completed')
                                        <span class="text-sm font-extrabold text-emerald-600">Completed</span>
                                    @elseif($job->status === 'In Progress')
                                        <span class="text-sm font-extrabold text-amber-600">In Progress</span>
                                    @elseif($job->status === 'Received')
                                        <span class="text-sm font-extrabold text-blue-600">Received</span>
                                    @else
                                        <span class="text-sm font-extrabold text-slate-600">{{ $job->status }}</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900 border-r border-slate-200 whitespace-nowrap">
                                    €{{ number_format($job->remaining_balance, 2) }}
                                </td>
                                <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                    <a 
                                        href="/repairs/{{ $job->ticket_number }}" 
                                        wire:navigate 
                                        class="px-3 py-1.5 bg-[#0a77c0] hover:bg-[#08629f] text-white rounded-sm text-sm font-bold shadow-2xs inline-block transition"
                                    >
                                        View & Pay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-slate-400 font-mono text-sm">
                                    No repair jobs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @endif
</div>
