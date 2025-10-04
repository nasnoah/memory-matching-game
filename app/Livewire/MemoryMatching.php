<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class MemoryMatching extends Component
{
    public string $difficulty;

    public int $maxItems;
    public bool $isChecking;

    #[Modelable]
    public bool $hasEnded;
    
    public array $items;
    public array $memoryItems;

    public function mount() 
    {
        $this->maxItems = $this->getMaxItems($this->difficulty);
        $this->isChecking = false;
        $this->hasEnded = false;

        $this->items = $this->getItems($this->maxItems);
        $this->memoryItems = $this->getMemoryItems($this->items);

        $this->dispatch('startGame');
        // dd($this->memoryItems);
    }

    public function getMaxItems(string $difficulty) {
        return match ($difficulty) {
            'easy'  => 12,
            'mid'   => 18,
            'hard'  => 24,
        };
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

    // State: 0 - hidden, 1 - shown, 2 - locked/correctly guessed
    public function getMemoryItems(array $items): array 
    {
        // $memoryItems = collect($items)->multiply(2)->shuffle()->toArray();
        $memoryItems = collect($items)->map(function ($item, $key) {
            $pairItems = collect($item)->multiply(2)->map(fn ($map) => ['pair_id' => $key, 'item' => $map, 'state' => 1])->toArray();
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

        $this->hasEnded();

        $this->isChecking = false;
    }

    public function startGame() {
        $this->memoryItems = collect($this->memoryItems)->map(function ($map) {
            $map['state'] = 0;
            return $map;
        })->toArray();
    }

    public function hasEnded() 
    {
        $countEndedItems = collect($this->memoryItems)->filter(fn ($item) => $item['state'] == 2)->count();
        $this->hasEnded = $countEndedItems == $this->maxItems;
    }

    public function render()
    {
        return view('livewire.memory-matching', [
            'memoryItems' => $this->memoryItems
        ]);
    }
}
