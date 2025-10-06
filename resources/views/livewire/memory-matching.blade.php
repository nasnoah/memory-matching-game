<div>
    {{-- Cards --}}
    <div class="grid grid-flow-col grid-rows-3 gap-5">
        {{-- Card --}}
        @foreach ($gameItems as $key => $item)
            <div wire:key="{{ $key.'-'.$item['state'] }}"
                class="relative w-48 h-64" wire:click="selectItem({{ $key }})">
                <div class="absolute w-48 h-64 bg-white rounded-lg shadow-md cursor-pointer z-10">
                    {{-- Image --}}
                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold select-none">
                        {{ $item['item'] }}
                    </div>
                    {{-- {{ $key.'-'.$item['pair_id'].'-'.$item['state'] }} --}}
                </div>
                <div :class="'{{ $item['state'] }}' == '0' ? 'z-10' : 'z-5'"
                    class="absolute w-48 h-64 bg-teal-600 rounded-lg shadow-md cursor-pointer">
                </div>
            </div>
        @endforeach
    </div>

    @script
        <script>
            $wire.on('startGame', (event) => {
                setTimeout(() => {
                    $wire.startGame();
                }, 3000);
            });

            $wire.on('delayedAmendingMatchingItems', (event) => {
                setTimeout(() => {
                    $wire.amendingMatchingItems(event.matchingItems, event.isMatching);
                }, 500); 
            });
        </script>
    @endscript
</div>
