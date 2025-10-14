<?php

namespace App\Livewire;

use App\Enums\GameStatus;
use App\Models\Game;
use App\Models\GameItem;
use App\Models\Level;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class MemoryMatching extends Component {

    public Level $level;
    public Game $game;

    public string $difficulty;

    public int $maxItems;
    public bool $isChecking;

    public bool $gameFinished;
    
    public ?Carbon $startAt;
    public ?Carbon $endAt;
    public $timeTaken;

    public int $moves;

    public array $items;
    public array $gameItems;

    public function mount() {
        $this->level ??= Level::first();
        $this->game ??= new Game();

        $this->maxItems = $this->getMaxItems($this->difficulty);
        $this->isChecking = false;
        $this->gameFinished = false;

        $this->startAt = null;
        $this->endAt = null;
        $this->timeTaken = null;

        $this->moves = 0;

        $this->items = [];
        $this->gameItems = [];

        $this->setupGame();

        $this->dispatch('startGame');
        // dd($this->gameItems);
    }

    public function setupGame() {
        $this->gameItems = $this->getGameItems($this->maxItems);

        $this->game->fill([
            'level_id'      => $this->level->id,
            'player_id'     => auth()->user()?->player?->id,
            'game_items'    => $this->gameItems,
        ])->save();
    }

    public function getMaxItems(string $difficulty) {
        return match ($difficulty) {
            'easy'  => 12,
            'mid'   => 18,
            'hard'  => 24,
        };
    }

    // State: 0 - hidden, 1 - shown, 2 - locked/correctly guessed
    public function getGameItems(int $maxItems): array {
        $availableGameItems = GameItem::pluck('name');

        $randomNeededGameItems = collect($availableGameItems)->shuffle()->take($maxItems/2);

        $gameItems = collect($randomNeededGameItems)->map(function ($item, $key) {
            $pairItems = collect($item)->multiply(2)->map(fn ($map) => ['pair_id' => $key, 'item' => $map, 'state' => 1])->toArray();
            return $pairItems;
        })->flatten(1)
            ->shuffle()
            ->toArray();

        return $gameItems;
    }

    public function selectItem($key) {
        if ($this->isChecking || $this->gameFinished) return;

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
            $this->moves++;

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

        // ? Unmnemo Mechanism
        $this->gameItems = $this->reshuffleUnmatchItems($this->gameItems);

        $this->isGameFinished();

        $this->isChecking = false;
    }

    public function reshuffleUnmatchItems($items) {
        $unmatchItems = collect($items)->filter(fn ($item) => $item['state'] == 0)->shuffle();

        $items = collect($items)->map(function ($item) use ($unmatchItems) {
            static $index = 0;

            if ($item['state'] == 0) {
                return $unmatchItems[$index++];
            }
            else {
                return $item;
            } 
        })->toArray();

        return $items;
    }

    public function startGame() {
        $this->gameItems = collect($this->gameItems)->map(function ($map) {
            $map['state'] = 0;
            return $map;
        })->toArray();

        $this->startAt = now();

        $this->game->fill([
            'game_items'    => $this->gameItems,
            'status'        => GameStatus::STARTED,
            'start_at'      => $this->startAt,
        ])->save();
    }

    public function isGameFinished() {
        $countEndedItems = collect($this->gameItems)->filter(fn ($item) => $item['state'] == 2)->count();
        $this->gameFinished = $countEndedItems == $this->maxItems;

        if ($this->gameFinished) {
            $this->endAt = now();
            $this->timeTaken = number_format($this->startAt->diffInSeconds($this->endAt), 2);

            $this->game->fill([
                'status'        => GameStatus::ENDED,
                'end_at'        => $this->endAt,
                'time_taken'    => $this->timeTaken,
                'moves'         => $this->moves,
                'scores'        => null // TODO: create score formula
            ])->save();
            
            $this->dispatch('game-finished', gameFinished: $this->gameFinished, timeTaken: $this->timeTaken, moves: $this->moves);
        }
    }

    public function render() {
        return view('livewire.memory-matching', [
            'gameItems' => $this->gameItems
        ]);
    }
}
