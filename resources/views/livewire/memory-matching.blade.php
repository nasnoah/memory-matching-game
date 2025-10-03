<div>
    {{-- Cards --}}
    <div class="grid grid-flow-col grid-rows-3 gap-5">
        {{-- Card --}}
        @foreach ($memoryItems as $item)
            <div x-data="{ 
                    flipped: '{{ $item['flipped'] }}',
                    label: '{{ $item['item'] . ' ' . $item['pair_id'] }}', 
                    init() {
                        let elements = $el.children;
                        for (let element of elements) {
                            element.style.zIndex = 10;
                        }
                        {{-- console.log(this.flipped); --}}
                    },
                    flip(e) {
                        this.init();
                        e.style.zIndex = 5;
                        this.flipped = this.flipped == 0 ? 1 : 0;
                        $wire.checkIfCorrectGuessing(); 
                        console.log(this.flipped); 
                    }
                }" 
                class="relative w-48 h-64">
                <div x-on:click="flip($el)" 
                    class="absolute w-48 h-64 bg-white rounded-lg shadow-md cursor-pointer">
                    {{-- Image --}}
                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold select-none">
                        <span x-text="label"></span>
                    </div>
                </div>
                <div x-on:click="flip($el)"  
                    class="absolute w-48 h-64 bg-teal-600 rounded-lg shadow-md cursor-pointer">
                </div>
            </div>
        @endforeach
    </div>
</div>
