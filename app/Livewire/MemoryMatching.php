<?php

namespace App\Livewire;

use Livewire\Component;

class MemoryMatching extends Component
{
    public int $maxItems;
    public array $items;
    public array $memoryItems;
    public bool $isChecking;

    public function mount() 
    {
        $this->maxItems = 12;
        $this->items = $this->getItems($this->maxItems);
        $this->memoryItems = $this->getMemoryItems($this->items);
        $this->isChecking = false;
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
            $pairItems = collect($item)->multiply(2)->map(fn ($map) => ['pair_id' => $key, 'item' => $map, 'state' => 0])->toArray();
            return $pairItems;
        })->flatten(1)
            ->shuffle()
            ->toArray();

        return $memoryItems;
    }

    public function matchingItem($key) 
    {
        if ($this->isChecking) return;

        $this->memoryItems = collect($this->memoryItems)->map(function ($item, $index) use ($key) { 
            if ($key == $index && $item['state'] != 2) {
                $item['state'] = 1;
            }
            return $item;
        })->toArray();

        $matchingItems = collect($this->memoryItems)->filter(fn ($item) => $item['state'] == 1);
        
        if ($matchingItems->count() == 2) {
            $this->isChecking = true;
            $this->dispatch('delayedCheckIfCorrectGuessing');
        }
        else {
            $this->checkIfCorrectGuessing();
        }
    }

    public function checkIfCorrectGuessing() 
    {
        $items = collect($this->memoryItems);

        $matchingItems = collect($items)->filter(fn ($item) => $item['state'] == 1);
        
        if ($matchingItems->count() == 2) {
            $isMatching = $matchingItems->pluck('pair_id')->duplicates()->count() != 0;
            
            $items = $items->map(function ($item, $index) use ($matchingItems, $isMatching) {
                if ($matchingItems->has($index)) {
                    $item['state'] = $isMatching ? 2 : 0;
                }
                return $item;
            });
        }
        
        $this->memoryItems = $items->toArray();

        $this->isChecking = false;
    }

    public function updatingMatchingItems() {

    }

    public function render()
    {
        return view('livewire.memory-matching', [
            'memoryItems' => $this->memoryItems
        ]);
    }
}
