<?php

namespace App\Livewire;

use Livewire\Component;

class MemoryMatching extends Component
{
    public int $maxItems;
    public array $items;
    public array $memoryItems;

    public function mount() 
    {
        $this->maxItems = 12;
        $this->items = $this->getItems($this->maxItems);
        $this->memoryItems = $this->getMemoryItems($this->items);

        // dd($this->memoryItems);
    }

    public function getItems(int $maxItems): array 
    {
        $availableItems = [
            'Air',
            'Bicycle',
            'Cat',
            'Dinasour',
            'Elephant',
            'Fan',
            'Grease',
            'Horse',
            'Internet',
            'Joker',
            'King',
            'Language',
        ];

        // $randomItems = collect($availableItems)->random($maxItems/2)->toArray();
        $randomItems = collect($availableItems)->shuffle()->take($maxItems/2)->toArray();

        return $randomItems;
    }

    public function getMemoryItems(array $items): array 
    {
        // $memoryItems = collect($items)->multiply(2)->shuffle()->toArray();
        $memoryItems = collect($items)->map(function ($item, $key) {
            $pairItems = collect($item)->multiply(2)->map(fn ($map) => ['pair_id' => $key, 'item' => $map, 'flipped' => 0])->toArray();
            return $pairItems;
        })->flatten(1)
            ->shuffle()
            ->toArray();

        return $memoryItems;
    }

    public function checkIfCorrectGuessing() 
    {
        // dd('test 123');
        $this->memoryItems = collect($this->memoryItems)->map(function ($item) { 
            $item['flipped'] = 1;
            return $item;
        })->toArray();
        // return true;
    }

    public function render()
    {
        return view('livewire.memory-matching', [
            'memoryItems' => $this->memoryItems
        ]);
    }
}
