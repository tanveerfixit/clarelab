<div 
    x-data="{ activeTileId: null }" 
    class="w-full bg-slate-50 border border-slate-200/80 rounded-sm p-2.5 shadow-2xs space-y-2 select-none"
>
    <!-- Tier 1: Horizontal Scrollable Speed Grid Tiles Row -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <template x-for="tile in $wire.tiles" :key="tile.id">
            <button 
                type="button"
                @click="activeTileId = (activeTileId === tile.id ? null : tile.id)"
                class="px-4 py-2 rounded-sm text-xs font-bold whitespace-nowrap cursor-pointer transition-colors flex items-center gap-2 shrink-0 border border-slate-900"
                :class="activeTileId === tile.id 
                    ? 'bg-white text-slate-900' 
                    : 'bg-slate-200 text-slate-900 hover:bg-slate-300'"
            >
                <span x-text="tile.name"></span>
            </button>
        </template>
    </div>

    <!-- Tier 2: Sub-Options Row (Conditionally Rendered with Smooth Transition) -->
    <div 
        x-show="activeTileId !== null" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="bg-white border border-slate-200 rounded-sm p-2.5 shadow-2xs"
        style="display: none;"
    >
        <template x-for="tile in $wire.tiles" :key="tile.id">
            <div x-show="activeTileId === tile.id" class="flex flex-wrap items-center gap-2">
                <template x-for="option in tile.options" :key="option.id">
                    <button 
                        type="button"
                        wire:click="handleSpeedGridSelection(option.id)"
                        class="px-3.5 py-2 bg-slate-100 hover:bg-slate-800 hover:text-white text-slate-900 border border-slate-200/80 rounded-sm text-xs font-bold cursor-pointer transition-all flex items-center gap-2 shadow-2xs group"
                    >
                        <span x-text="option.label"></span>
                        <span class="px-1.5 py-0.5 bg-white group-hover:bg-slate-700 text-slate-800 group-hover:text-white rounded text-[11px] font-mono font-bold border border-slate-200 group-hover:border-slate-600">
                            €<span x-text="parseFloat(option.price).toFixed(2)"></span>
                        </span>
                    </button>
                </template>
            </div>
        </template>
    </div>
</div>
