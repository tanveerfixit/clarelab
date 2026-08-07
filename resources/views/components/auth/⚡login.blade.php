<div class="w-full max-w-md mx-auto">
    
    <!-- Top Branding & Logo Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0070BA]/10 text-[#0070BA] mb-4">
            <!-- Phone Device Icon -->
            <svg class="w-9 h-9 text-[#0070BA]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Login to your Business</h1>
        <p class="text-sm text-slate-500 font-medium mt-1.5">Enter your details below to access {{ $branchName }}</p>
    </div>

    <!-- Error Alert Message -->
    @if($errorMessage)
        <div class="mb-5 bg-red-50 border border-red-200 p-4 rounded-xl text-sm text-red-700 flex items-center justify-between font-medium shadow-2xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Incorrect email or password.</span>
            </div>
            <button wire:click="$set('errorMessage', null)" class="text-red-400 hover:text-red-600 font-bold text-sm cursor-pointer ml-2">✕</button>
        </div>
    @endif

    <!-- Senior Developer Light Mode Card Form -->
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm p-8 md:p-9">
        
        <form wire:submit.prevent="login" class="space-y-5" x-data="{ showPassword: false }">
            
            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-slate-700">Email Address <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input 
                        id="email"
                        name="email"
                        type="email" 
                        wire:model.defer="email"
                        placeholder="you@phonelab.com"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border @error('email') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#0070BA] focus:ring-[#0070BA]/20 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 transition duration-150 placeholder:text-slate-400 font-medium"
                        required
                        autofocus
                    />
                    <svg class="w-5 h-5 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                @error('email')
                    <span class="text-xs font-semibold text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input 
                        id="password"
                        name="password"
                        :type="showPassword ? 'text' : 'password'" 
                        wire:model.defer="password"
                        placeholder="••••••••"
                        class="w-full pl-11 pr-11 py-3 bg-slate-50 border @error('password') border-red-500 focus:ring-red-500/20 @else border-slate-200 focus:border-[#0070BA] focus:ring-[#0070BA]/20 @enderror rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 transition duration-150 placeholder:text-slate-400 font-medium"
                        required
                    />
                    <!-- Lock Icon -->
                    <svg class="w-5 h-5 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <!-- Eye Toggle Button -->
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword"
                        class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                        title="Show or hide password"
                    >
                        <template x-if="!showPassword">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </template>
                        <template x-if="showPassword">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a8.962 8.962 0 013.682-.763c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m-1.4 1.4A8.98 8.98 0 0112 19c-1.4 0-2.732-.322-3.925-.902M3 3l18 18"/>
                            </svg>
                        </template>
                    </button>
                </div>
                @error('password')
                    <span class="text-xs font-semibold text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me Option -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2.5 cursor-pointer text-slate-700 font-medium select-none">
                    <input 
                        type="checkbox" 
                        wire:model="remember"
                        class="w-4 h-4 rounded-md border-slate-300 text-[#0070BA] focus:ring-[#0070BA]"
                    />
                    <span>Remember me</span>
                </label>
            </div>

            <!-- Human Verification (Math CAPTCHA) -->
            <div class="space-y-2">
                <label for="verification_answer" class="block text-sm font-semibold text-slate-700">
                    Human Verification: What is <span class="font-extrabold text-slate-900">{{ $num1 }} + {{ $num2 }}</span>? <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input 
                        id="verification_answer"
                        name="verification_answer"
                        type="number" 
                        wire:model.defer="verification_answer"
                        placeholder="Answer"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 focus:border-[#0070BA] focus:ring-[#0070BA]/20 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 transition duration-150 placeholder:text-slate-400 font-medium font-mono"
                        required
                    />
                    <svg class="w-5 h-5 absolute left-3.5 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full py-3 px-5 bg-[#0070BA] hover:bg-[#005B96] active:bg-[#004878] text-white font-bold text-sm rounded-xl shadow-xs hover:shadow transition duration-200 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75"
                >
                    <span wire:loading.remove wire:target="login">Login to Business</span>
                    <span wire:loading wire:target="login" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Signing in...</span>
                    </span>
                </button>
            </div>

        </form>
    </div>

</div>
