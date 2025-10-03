<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="bg-[#FDFDFC] text-[#1b1b18]">
        <div class="flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
            {{-- Cards --}}
            <div class="grid grid-flow-col grid-rows-3 gap-5">
                {{-- Card --}}
                @for ($i = 0; $i < 12; $i++)
                    <div x-data="{ 
                            label: 'TEST', 
                            init() {
                                let elements = $el.children;
                                for (let element of elements) {
                                    element.style.zIndex = 10;
                                }
                            },
                            flip(e) {
                                this.init();
                                e.style.zIndex = 5;
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
                @endfor
            </div>
        </div>

        @livewireScripts
    </body>
</html>
