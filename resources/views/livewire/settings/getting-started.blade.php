<div class="flex flex-col h-screen w-full font-sans bg-[#F4F7F9]">
    <!-- Top Header -->
    <div class="px-6 py-3 bg-white border-b border-slate-200 flex-shrink-0">
        <h1 class="text-lg font-bold text-slate-800">Getting Started</h1>
    </div>

    <!-- Main Workspace Container -->
    <div class="flex-1 flex min-h-0">
        <!-- Left Sidebar Navigation -->
        <div class="w-64 bg-white border-r border-slate-200 flex-shrink-0 overflow-y-auto">
            <nav class="flex flex-col">
                <button 
                    wire:click="setTab('account-setup')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'account-setup' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Account Setup
                </button>
                <button 
                    wire:click="setTab('company-info')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'company-info' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Company Information
                </button>
                <button 
                    wire:click="setTab('taxes')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'taxes' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Manage Taxes
                </button>
                <button 
                    wire:click="setTab('payment-options')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'payment-options' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Payment Options
                </button>
                <button 
                    wire:click="setTab('import-customers')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'import-customers' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Import Customers
                </button>
                <button 
                    wire:click="setTab('import-products')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'import-products' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Import Products
                </button>
                <button 
                    wire:click="setTab('label-printer')" 
                    class="w-full text-left px-5 py-3 text-sm font-semibold transition-colors duration-150 border-b border-slate-100 cursor-pointer {{ $activeTab === 'label-printer' ? 'bg-[#E9F0F4] text-[#0285B5] border-l-4 border-[#0285B5] pl-4' : 'text-slate-700 hover:bg-slate-50' }}"
                >
                    Manage Label Printer
                </button>
            </nav>
        </div>

        <!-- Right Content Section (Full width, scrollable) -->
        <div class="flex-1 p-6 overflow-y-auto space-y-4">
            
            <!-- Success Alert -->
            @if($successMessage)
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $successMessage }}</span>
                </div>
            @endif

            <!-- 1. Account Setup Tab -->
            @if($activeTab === 'account-setup')
                <div class="bg-white border border-slate-200">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-base font-bold text-slate-900">Accounts Setup</h2>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            This is where you'll configure your basic account settings and preferences to get your system up and running. Complete these essential steps to personalize your experience.
                        </p>
                    </div>

                    <form wire:submit.prevent="saveAccountSetup" class="p-5 space-y-5">
                        <!-- Currency -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="currency" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Currency<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <select 
                                    wire:model="currency" 
                                    id="currency" 
                                    class="w-full border border-slate-300 bg-slate-100 px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                >
                                    <option value="EUR">€, Euro</option>
                                    <option value="USD">$, US Dollar</option>
                                    <option value="GBP">£, British Pound</option>
                                </select>
                                @error('currency') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Time Zone -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="timezone" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Time Zone<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <select 
                                    wire:model="timezone" 
                                    id="timezone" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                >
                                    <option value="Europe/London">UTC/GMT +01:00 - Europe/London</option>
                                    <option value="UTC">UTC/GMT +00:00 - UTC</option>
                                    <option value="America/New_York">UTC/GMT -05:00 - America/New_York</option>
                                    <option value="Asia/Singapore">UTC/GMT +08:00 - Asia/Singapore</option>
                                </select>
                                @error('timezone') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Date Format -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="date_format" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Date Format<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <select 
                                    wire:model="date_format" 
                                    id="date_format" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                >
                                    <option value="DD-MM-YY">DD-MM-YY</option>
                                    <option value="MM-DD-YY">MM-DD-YY</option>
                                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                </select>
                                @error('date_format') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Time Format -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="time_format" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Time Format<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <select 
                                    wire:model="time_format" 
                                    id="time_format" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                >
                                    <option value="12 hour">12 hour</option>
                                    <option value="24 hour">24 hour</option>
                                </select>
                                @error('time_format') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Language -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="language" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Language<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <select 
                                    wire:model="language" 
                                    id="language" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                >
                                    <option value="English">English</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="French">French</option>
                                    <option value="German">German</option>
                                </select>
                                @error('language') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button 
                                type="submit" 
                                class="px-4 py-1.5 bg-[#0285B5] hover:brightness-110 text-white font-bold text-xs cursor-pointer"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 2. Company Information Tab -->
            @if($activeTab === 'company-info')
                <div class="bg-white border border-slate-200">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-base font-bold text-slate-900">Company Information</h2>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Configure your business details, contact information, and primary physical address settings.
                        </p>
                    </div>

                    <form wire:submit.prevent="saveCompanyInfo" class="p-5 space-y-5">
                        <!-- Sub-Domain -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="subdomain" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Sub-Domain
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="subdomain" 
                                    id="subdomain" 
                                    readonly
                                    class="w-full border border-slate-300 bg-slate-100 px-3 py-1.5 text-xs text-slate-500 cursor-not-allowed outline-hidden"
                                />
                            </div>
                        </div>

                        <!-- Company Name -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="company_name" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Company Name<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="company_name" 
                                    id="company_name" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('company_name') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Company Phone No -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="company_phone" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Company Phone No.<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="company_phone" 
                                    id="company_phone" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('company_phone') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Customer Service Email -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="company_email" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Customer Service Email<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="email" 
                                    wire:model="company_email" 
                                    id="company_email" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('company_email') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Street Address -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="street_address" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Street Address<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="street_address" 
                                    id="street_address" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('street_address') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- City -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="city" class="md:col-span-1 text-xs font-bold text-slate-700">
                                City<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="city" 
                                    id="city" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('city') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- State / Province -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="state" class="md:col-span-1 text-xs font-bold text-slate-700">
                                State / Province<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="state" 
                                    id="state" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('state') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Zip/Postal Code -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="zip_code" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Zip/Postal Code<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="zip_code" 
                                    id="zip_code" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('zip_code') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Country -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <label for="country" class="md:col-span-1 text-xs font-bold text-slate-700">
                                Country<span class="text-red-500">*</span>
                            </label>
                            <div class="md:col-span-3">
                                <input 
                                    type="text" 
                                    wire:model="country" 
                                    id="country" 
                                    class="w-full border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:border-[#0285B5] focus:outline-hidden"
                                />
                                @error('country') <span class="text-[10px] text-red-600 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button 
                                type="submit" 
                                class="px-4 py-1.5 bg-[#0285B5] hover:brightness-110 text-white font-bold text-xs cursor-pointer"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- 3. Manage Label Printer Tab -->
            @if($activeTab === 'label-printer')
                <div class="bg-white border border-slate-200">
                    <div class="p-5 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-base font-bold text-slate-900">Manage Label Printer</h2>
                    </div>

                    <form wire:submit.prevent="saveLabelPrinter" class="p-5 space-y-5">
                        <!-- Label Size -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700 block">Label Size</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <label class="flex items-start gap-2.5 p-2.5 border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" wire:model.live="label_size" value="dymo_30334" class="mt-0.5 text-[#0285B5] focus:ring-[#0285B5]" />
                                    <div class="text-[11px]">
                                        <span class="font-bold text-slate-900 block">Dymo 30334</span>
                                        <span class="text-slate-500">2.25" (57mm) x 1.25" (32mm)</span>
                                    </div>
                                </label>
                                <label class="flex items-start gap-2.5 p-2.5 border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" wire:model.live="label_size" value="dymo_30336" class="mt-0.5 text-[#0285B5] focus:ring-[#0285B5]" />
                                    <div class="text-[11px]">
                                        <span class="font-bold text-slate-900 block">Dymo 30336</span>
                                        <span class="text-slate-500">2.12" (54mm) x 1" (25mm)</span>
                                    </div>
                                </label>
                                <label class="flex items-start gap-2.5 p-2.5 border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" wire:model.live="label_size" value="brother_dk1209" class="mt-0.5 text-[#0285B5] focus:ring-[#0285B5]" />
                                    <div class="text-[11px]">
                                        <span class="font-bold text-slate-900 block">Brother DK1209</span>
                                        <span class="text-slate-500">2.4" (62mm) x 1.1" (28mm)</span>
                                    </div>
                                </label>
                                <label class="flex items-start gap-2.5 p-2.5 border border-slate-200 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" wire:model.live="label_size" value="custom" class="mt-0.5 text-[#0285B5] focus:ring-[#0285B5]" />
                                    <div class="text-[11px]">
                                        <span class="font-bold text-slate-900 block">Custom</span>
                                        <span class="text-slate-500">Specify custom sizes below</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Warnings block -->
                        <div class="p-3 bg-amber-50 border border-amber-100 text-[11px] text-amber-900 space-y-1">
                            <span class="font-bold block">The label-size you chose:</span>
                            <ul class="list-disc pl-4 space-y-0.5">
                                <li>might produce tiny barcode, so please consider checking Preview with your Scanner</li>
                                <li>might contain 6 lines of information including Barcode</li>
                                <li>might contain 32 characters in each line</li>
                            </ul>
                        </div>

                        <!-- Fields Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Column 1 -->
                            <div class="space-y-4">
                                <!-- Barcode Length -->
                                <div class="grid grid-cols-3 gap-2 items-center">
                                    <label for="barcode_length" class="text-xs font-bold text-slate-700">Regular Barcode Length</label>
                                    <div class="col-span-2 flex items-center gap-1.5">
                                        <input type="number" wire:model.live="barcode_length" id="barcode_length" class="w-16 border border-slate-300 px-2 py-1 text-xs text-slate-900" />
                                        <span class="text-xs text-slate-500">character</span>
                                    </div>
                                </div>

                                <!-- Margins -->
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-700 block">Margins</label>
                                    <div class="grid grid-cols-4 gap-2">
                                        <div>
                                            <label for="margin_top" class="text-[9px] text-slate-500 uppercase block">Top</label>
                                            <input type="number" wire:model.live="margin_top" id="margin_top" class="w-full border border-slate-300 px-1 py-0.5 text-xs text-center text-slate-900" />
                                        </div>
                                        <div>
                                            <label for="margin_left" class="text-[9px] text-slate-500 uppercase block">Left</label>
                                            <input type="number" wire:model.live="margin_left" id="margin_left" class="w-full border border-slate-300 px-1 py-0.5 text-xs text-center text-slate-900" />
                                        </div>
                                        <div>
                                            <label for="margin_bottom" class="text-[9px] text-slate-500 uppercase block">Bottom</label>
                                            <input type="number" wire:model.live="margin_bottom" id="margin_bottom" class="w-full border border-slate-300 px-1 py-0.5 text-xs text-center text-slate-900" />
                                        </div>
                                        <div>
                                            <label for="margin_right" class="text-[9px] text-slate-500 uppercase block">Right</label>
                                            <input type="number" wire:model.live="margin_right" id="margin_right" class="w-full border border-slate-300 px-1 py-0.5 text-xs text-center text-slate-900" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="space-y-4">
                                <!-- Orientation -->
                                <div class="grid grid-cols-3 gap-2 items-center">
                                    <label for="orientation" class="text-xs font-bold text-slate-700">Orientation</label>
                                    <select wire:model.live="orientation" id="orientation" class="col-span-2 border border-slate-300 px-2 py-1 text-xs text-slate-900 bg-white">
                                        <option value="Landscape">Landscape</option>
                                        <option value="Portrait">Portrait</option>
                                    </select>
                                </div>

                                <!-- Font Size -->
                                <div class="grid grid-cols-3 gap-2 items-center">
                                    <label for="font_size" class="text-xs font-bold text-slate-700">Font Size</label>
                                    <select wire:model.live="font_size" id="font_size" class="col-span-2 border border-slate-300 px-2 py-1 text-xs text-slate-900 bg-white">
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Large">Large</option>
                                    </select>
                                </div>

                                <!-- Font Family -->
                                <div class="grid grid-cols-3 gap-2 items-center">
                                    <label for="font_family" class="text-xs font-bold text-slate-700">Font Family</label>
                                    <select wire:model.live="font_family" id="font_family" class="col-span-2 border border-slate-300 px-2 py-1 text-xs text-slate-900 bg-white">
                                        <option value="Arial Black">Arial Black</option>
                                        <option value="Arial">Arial</option>
                                        <option value="Courier New">Courier New</option>
                                        <option value="Times New Roman">Times New Roman</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Sample label block -->
                        <div class="pt-5 border-t border-slate-100 space-y-3">
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                This is a sample label. Please make a test print and you should be able to see all 4 sides of the border around this label. If you do not then you need to increase the margin on any side you do not see when printed until you do see it.
                            </p>

                            <!-- Outer box containing live preview container -->
                            <div class="flex justify-center bg-slate-50 p-4 border border-slate-200">
                                <!-- Styled preview box -->
                                <div 
                                    id="label-preview-box"
                                    class="bg-white border border-slate-400 text-slate-900 flex flex-col justify-between"
                                    style="
                                        font-family: '{{ $font_family === 'Arial Black' ? 'Arial Black, Impact' : $font_family }}';
                                        padding-top: {{ $margin_top }}px;
                                        padding-left: {{ $margin_left }}px;
                                        padding-bottom: {{ $margin_bottom }}px;
                                        padding-right: {{ $margin_right }}px;
                                        font-size: {{ $font_size === 'Large' ? '13px' : ($font_size === 'Medium' ? '11px' : '9px') }};
                                        width: {{ $orientation === 'Landscape' ? '250px' : '150px' }};
                                        height: {{ $orientation === 'Landscape' ? '130px' : '200px' }};
                                    "
                                >
                                    <div class="space-y-0.5 leading-tight overflow-hidden text-center">
                                        <div class="font-bold">Cell-Store</div>
                                        <div>iPhone-11</div>
                                        <div class="font-bold text-xs">$250.00</div>
                                        <div class="text-[9px] text-slate-600">Cell-Store</div>
                                        <div class="text-[9px] text-slate-600 truncate">iPhone-11...</div>
                                    </div>

                                    <div class="mt-1 text-center">
                                        <svg class="mx-auto w-full max-w-[180px]" height="28" viewBox="0 0 100 35" preserveAspectRatio="none">
                                            <g fill="currentColor">
                                                <rect x="0" y="0" width="2" height="30" />
                                                <rect x="3" y="0" width="1" height="30" />
                                                <rect x="6" y="0" width="3" height="30" />
                                                <rect x="11" y="0" width="1" height="30" />
                                                <rect x="14" y="0" width="2" height="30" />
                                                <rect x="18" y="0" width="4" height="30" />
                                                <rect x="24" y="0" width="1" height="30" />
                                                <rect x="27" y="0" width="2" height="30" />
                                                <rect x="31" y="0" width="1" height="30" />
                                                <rect x="34" y="0" width="3" height="30" />
                                                <rect x="39" y="0" width="1" height="30" />
                                                <rect x="42" y="0" width="2" height="30" />
                                                <rect x="46" y="0" width="4" height="30" />
                                                <rect x="52" y="0" width="1" height="30" />
                                                <rect x="55" y="0" width="2" height="30" />
                                                <rect x="59" y="0" width="3" height="30" />
                                                <rect x="64" y="0" width="1" height="30" />
                                                <rect x="67" y="0" width="2" height="30" />
                                                <rect x="71" y="0" width="4" height="30" />
                                                <rect x="77" y="0" width="1" height="30" />
                                                <rect x="80" y="0" width="2" height="30" />
                                                <rect x="84" y="0" width="1" height="30" />
                                                <rect x="87" y="0" width="3" height="30" />
                                                <rect x="92" y="0" width="1" height="30" />
                                                <rect x="95" y="0" width="2" height="30" />
                                            </g>
                                        </svg>
                                        <div class="text-[8px] font-mono tracking-widest leading-none mt-0.5">ÌFGR14528978563214555TÎ</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end pt-4 border-t border-slate-100 gap-2">
                            <button 
                                type="button" 
                                onclick="printSampleLabel()"
                                class="px-4 py-1.5 bg-slate-800 hover:brightness-110 text-white font-bold text-xs cursor-pointer"
                            >
                                Sample Print
                            </button>
                            <button 
                                type="submit" 
                                class="px-4 py-1.5 bg-[#0285B5] hover:brightness-110 text-white font-bold text-xs cursor-pointer"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Placeholders for other tabs -->
            @if(!in_array($activeTab, ['account-setup', 'company-info', 'label-printer']))
                <div class="bg-white border border-slate-200 p-8 text-center space-y-4">
                    <div class="mx-auto w-10 h-10 bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">
                            {{ ucwords(str_replace('-', ' ', $activeTab)) }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            This section is ready for configuration. Provide the layout details to build it.
                        </p>
                    </div>
                </div>
            @endif

        </div>

    </div>
    
    <!-- Hidden Print Helper Script -->
    <script>
    function printSampleLabel() {
        const element = document.getElementById('label-preview-box');
        if (!element) return;
        
        // Resolve selected label size to configure page layout sizes
        const selectedSize = document.querySelector('input[wire\\:model="label_size"]:checked')?.value || 
                             document.querySelector('input[model\\.live="label_size"]:checked')?.value ||
                             'dymo_30334';
        
        let pageCssRule = 'size: 2.25in 1.25in; margin: 0;';
        let printBoxWidth = '2.25in';
        let printBoxHeight = '1.25in';
        
        if (selectedSize === 'dymo_30336') {
            pageCssRule = 'size: 2.12in 1in; margin: 0;';
            printBoxWidth = '2.12in';
            printBoxHeight = '1in';
        } else if (selectedSize === 'brother_dk1209') {
            pageCssRule = 'size: 2.4in 1.1in; margin: 0;';
            printBoxWidth = '2.4in';
            printBoxHeight = '1.1in';
        } else if (selectedSize === 'custom') {
            pageCssRule = 'size: auto; margin: 0;';
            printBoxWidth = '100%';
            printBoxHeight = 'auto';
        }
        
        const printWindow = window.open('', '_blank', 'width=450,height=350');
        
        printWindow.document.write('<html><head><title>Sample Label Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { ' + pageCssRule + ' }');
        printWindow.document.write('body { margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; font-family: sans-serif; background-color: #fff; }');
        printWindow.document.write('.preview-box { border: 1px solid #000; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between; background-color: #fff; color: #000; overflow: hidden; }');
        printWindow.document.write('.font-bold { font-weight: bold; }');
        printWindow.document.write('.text-xs { font-size: 11px; }');
        printWindow.document.write('.text-slate-600 { color: #475569; }');
        printWindow.document.write('.font-mono { font-family: monospace; }');
        printWindow.document.write('.tracking-widest { letter-spacing: 0.1em; }');
        printWindow.document.write('.text-center { text-align: center; }');
        printWindow.document.write('.mt-1 { margin-top: 4px; }');
        printWindow.document.write('.mt-2 { margin-top: 8px; }');
        printWindow.document.write('.space-y-0\\.5 > * + * { margin-top: 2px; }');
        printWindow.document.write('.truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }');
        printWindow.document.write('svg { display: block; margin: 0 auto; max-width: 100%; }');
        printWindow.document.write('@media print { body { padding: 0; width: ' + printBoxWidth + '; height: ' + printBoxHeight + '; } .preview-box { border: 1px solid #000; width: 100% !important; height: 100% !important; } }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        
        const clone = element.cloneNode(true);
        clone.className = 'preview-box';
        // Force the cloned preview-box to use full print window bounds when printing
        clone.style.width = '100%';
        clone.style.height = '100%';
        
        printWindow.document.write(clone.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 300);
    }
    </script>
</div>
