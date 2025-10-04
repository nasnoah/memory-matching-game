<div>
    @if ($this->isStarting)
        @if ($this->hasEnded)
            <div class="flex flex-col items-center gap-10">
                <div class="text-5xl font-bold">
                    Congratulations, You Won !!!
                </div>

                <div class="flex items-center gap-3">
                    <div wire:click="restartGame" 
                        class="w-fit px-4 py-2 bg-gray-800 text-white text-2xl rounded-lg shadow-md cursor-pointer hover:bg-gray-700">
                        Restart Game
                    </div>
                    
                    <div wire:click="backToMainMenu" 
                        class="w-fit px-4 py-2 bg-white text-black text-2xl rounded-lg shadow-md cursor-pointer hover:bg-gray-100">
                        Back To Main Menu
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center gap-10 ">
                <livewire:memory-matching wire:model.live="hasEnded" :$difficulty :key="$difficulty.now()->timestamp" />
                    
                <div wire:click="stopGame" 
                    class="w-fit px-4 py-2 bg-gray-800 text-white text-2xl rounded-lg shadow-md cursor-pointer hover:bg-gray-700">
                    Stop Game
                </div>
            </div>
        @endif
    @else
        <div class="flex flex-col items-center gap-10">
            <div class="mb-10 text-7xl font-extrabold">
                Memory Matching Game
            </div>
            
            <div class="flex items-center gap-5">
                <div wire:click="selectDifficulty('easy')"
                    :class="'{{ $this->difficulty }}' == 'easy' ? 'scale-125 border-2 border-black' : ''" 
                    class="w-20 h-20 bg-green-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer transition ease-out">
                    <span class="text-center text-2xl font-bold text-white">Easy</span>
                </div>
                <div wire:click="selectDifficulty('mid')" 
                    :class="'{{ $this->difficulty }}' == 'mid' ? 'scale-125 border-2 border-black' : ''"
                    class="w-20 h-20 bg-yellow-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer transition ease-out">
                    <span class="text-center text-2xl font-bold text-white">Mid</span>
                </div>
                <div wire:click="selectDifficulty('hard')" 
                    :class="'{{ $this->difficulty }}' == 'hard' ? 'scale-125 border-2 border-black' : ''"
                    class="w-20 h-20 bg-red-500 rounded-full shadow-lg flex items-center justify-center cursor-pointer transition ease-out">
                    <span class="text-center text-2xl font-bold text-white">Hard</span>
                </div>
            </div>

            <div wire:click="startGame" 
                class="w-fit px-4 py-2 bg-gray-800 text-white text-2xl rounded-lg shadow-md cursor-pointer hover:bg-gray-700">
                Start Game
            </div>
        </div>
    @endif
</div>
