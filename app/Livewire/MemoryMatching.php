<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class MemoryMatching extends Component {

    public string $difficulty;

    public int $maxItems;
    public bool $isChecking;

    #[Modelable]
    public bool $gameFinished;
    
    public array $items;
    public array $gameItems;

    public function mount() {
        $this->maxItems = $this->getMaxItems($this->difficulty);
        $this->isChecking = false;
        $this->gameFinished = false;

        $this->items = $this->getItems($this->maxItems);
        $this->gameItems = $this->getGameItems($this->items);

        $this->dispatch('startGame');
        // dd($this->gameItems);
    }

    public function getMaxItems(string $difficulty) {
        return match ($difficulty) {
            'easy'  => 12,
            'mid'   => 18,
            'hard'  => 24,
        };
    }

    public function getItems(int $maxItems): array {
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
    public function getGameItems(array $items): array {
        // $gameItems = collect($items)->multiply(2)->shuffle()->toArray();
        $gameItems = collect($items)->map(function ($item, $key) {
            $pairItems = collect($item)->multiply(2)->map(fn ($map) => ['pair_id' => $key, 'item' => $map, 'state' => 1])->toArray();
            return $pairItems;
        })->flatten(1)
            ->shuffle()
            ->toArray();

        return $gameItems;
    }

    public function matchingItem($key) {
        if ($this->isChecking) return;

        $this->gameItems = collect($this->gameItems)->map(function ($item, $index) use ($key) { 
            if ($key == $index && $item['state'] != 2) {
                $item['state'] = 1;
            }
            return $item;
        })->toArray();
        
        $this->checkMatch();
    }

    public function checkMatch() {
        $items = collect($this->gameItems);

        $matchingItems = collect($items)->filter(fn ($item) => $item['state'] == 1);

        if ($matchingItems->count() == 2) {
            $this->isChecking = true;

            $isMatching = $matchingItems->pluck('pair_id')->duplicates()->count() != 0;
            
            if ($isMatching) {
                $this->amendingMatchingItems($matchingItems, $isMatching);
            }
            else {
                $this->dispatch('delayedAmendingMatchingItems', matchingItems: $matchingItems, isMatching: $isMatching);
            }
        }
    }

    public function amendingMatchingItems($matchingItems, $isMatching) {
        $matchingItems = collect($matchingItems);

        $this->gameItems = collect($this->gameItems)->map(function ($item, $index) use ($matchingItems, $isMatching) {
            if ($matchingItems->has($index)) {
                $item['state'] = $isMatching ? 2 : 0;
            }
            return $item;
        })->toArray();       

        $this->isGameFinished();

        $this->isChecking = false;
    }

    public function startGame() {
        $this->gameItems = collect($this->gameItems)->map(function ($map) {
            $map['state'] = 0;
            return $map;
        })->toArray();
    }

    public function isGameFinished() {
        $countEndedItems = collect($this->gameItems)->filter(fn ($item) => $item['state'] == 2)->count();
        $this->gameFinished = $countEndedItems == $this->maxItems;
    }

    public function render() {
        return view('livewire.memory-matching', [
            'gameItems' => $this->gameItems
        ]);
    }
}
