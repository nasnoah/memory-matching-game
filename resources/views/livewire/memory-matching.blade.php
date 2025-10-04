<div>
    {{-- Cards --}}
    <div class="grid grid-flow-col grid-rows-3 gap-5">
        {{-- Card --}}
        @foreach ($memoryItems as $key => $item)
            <div wire:key="{{ $key.'-'.$item['state'] }}"
                class="relative w-48 h-64" wire:click="matchingItem({{ $key }})">
                <div 
                    {{-- x-effect="$el.style.zIndex = '{{ $item['state'] }}' == 2 ? 20 : 10" --}}
                    class="absolute w-48 h-64 bg-white rounded-lg shadow-md cursor-pointer z-10">
                    {{-- Image --}}
                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold select-none">
                        {{ $item['item'] }}
                    </div>
                    {{-- {{ $key.'-'.$item['pair_id'].'-'.$item['state'] }} --}}
                </div>
                <div 
                    {{-- x-effect="$el.style.zIndex = '{{ $item['state'] }}' == 0 ? 10 : 5" --}}
                    :class="'{{ $item['state'] }}' == '0' ? 'z-10' : 'z-5'"
                    class="absolute w-48 h-64 bg-teal-600 rounded-lg shadow-md cursor-pointer">
                </div>
            </div>
        @endforeach
    </div>

    @script
        <script>
            $wire.on('delayedCheckIfCorrectGuessing', (event) => {
                setTimeout(() => {
                    $wire.checkIfCorrectGuessing();
                }, 500); 
            });
        </script>
    @endscript
</div>
