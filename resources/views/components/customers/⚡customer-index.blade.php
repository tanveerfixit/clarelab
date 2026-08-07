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

    <!-- Header Bar: Title + CRM Button + Create Customer Button -->
    <div class="flex items-center justify-between pb-3.5 mb-2">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manage Customers</h1>

        <div class="flex items-center gap-2">
            <!-- CRM Megaphone Button -->
            <button 
                class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xs transition shadow-2xs flex items-center gap-2 cursor-pointer"
                onclick="alert('CRM Campaigns module is coming soon!')"
            >
                <span>📢</span>
                <span>CRM</span>
            </button>

            <!-- Yellow + Create Customer Button -->
            <button 
                wire:click="openCreateModal"
                class="px-4 py-2 bg-[#FFD700] hover:bg-[#E6C200] text-black font-bold text-sm rounded-xs transition shadow-2xs flex items-center gap-2 cursor-pointer border border-yellow-500/40"
            >
                <span class="font-extrabold text-base">+</span>
                <span>Create Customer</span>
            </button>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 text-sm">
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- 1. Status Filter Dropdown -->
            <select 
                wire:model.live="selectedType"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[160px] cursor-pointer"
            >
                <option value="all">All</option>
                <option value="completed">Completed</option>
                <option value="booked">Booked</option>
                <option value="processing">Processing</option>
            </select>

            <!-- 2. Sort Field Dropdown -->
            <select 
                wire:model.live="sortBy"
                class="px-3 py-2 bg-white border border-slate-300 rounded-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans min-w-[160px] cursor-pointer"
            >
                <option value="name">Sort by Name</option>
                <option value="company">Sort by Company</option>
                <option value="email">Sort by Email</option>
            </select>
        </div>

        <!-- 3. Search input -->
        <div class="flex items-center gap-0 w-full sm:w-auto">
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search"
                placeholder="Search Customers" 
                class="px-3.5 py-2 bg-white border border-slate-300 rounded-l-xs text-sm text-slate-800 focus:outline-none focus:border-slate-500 font-sans w-64"
            />
            <button class="px-3 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 border border-l-0 border-slate-300 rounded-r-xs cursor-pointer flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Full Width Customer Table List -->
    <div class="bg-white border border-slate-300 rounded-xs shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-base">
                <thead class="bg-[#E2E8F0] border-b border-slate-300 text-slate-900 font-extrabold text-base whitespace-nowrap">
                    <tr>
                        <th class="py-2.5 px-3 border-r border-slate-300">Name</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Email</th>
                        <th class="py-2.5 px-3 border-r border-slate-300">Contact No</th>
                        <th class="py-2.5 px-3 border-r border-slate-300 text-center">Status</th>
                        <th class="py-2.5 px-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-base whitespace-nowrap">
                    @forelse($customers as $cust)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-2 px-3 border-r border-slate-200 font-bold">
                                <a href="/customers/{{ $cust->slug }}" wire:navigate class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $cust->name }}
                                </a>
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-slate-500">
                                {{ $cust->email ?: '-' }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-slate-700">
                                {{ $cust->phone ?: '-' }}
                            </td>
                            <td class="py-2 px-3 border-r border-slate-200 text-center">
                                @if($cust->status === 'Processing')
                                    <span class="text-sm font-extrabold text-blue-600">
                                        Processing
                                    </span>
                                @elseif($cust->status === 'Booked')
                                    <span class="text-sm font-extrabold text-amber-600">
                                        Booked
                                    </span>
                                @else
                                    <span class="text-sm font-extrabold text-green-600">
                                        Completed
                                    </span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                <a 
                                    href="/customers/{{ $cust->slug }}" 
                                    wire:navigate
                                    class="text-blue-600 hover:text-blue-800 font-bold hover:underline"
                                >
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-medium bg-slate-50/50">
                                No customer records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($customers->hasPages())
            <div class="border-t border-slate-300 p-3 bg-slate-50/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

    <!-- Create Customer Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-300 rounded-xs shadow-xl w-full max-w-md overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-[#E2E8F0] border-b border-slate-300 px-4 py-3 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 text-base">Create New Customer</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-lg cursor-pointer">✕</button>
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
                            wire:click="$set('showCreateModal', false)" 
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
