<?php

namespace App\Livewire;

use App\Models\Player;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class MainMenu extends Component {

    public bool $gameStarted;
    public bool $gameFinished;

    public string $difficulty; // easy, mid, hard

    public $timeTaken;
    public int $moves;

    public function mount() {
        $this->gameStarted = false;
        $this->gameFinished = false;

        $this->difficulty = 'easy';

        $this->timeTaken = null;
        $this->moves = 0;
    }

    public function startGame() {
        $this->gameStarted = true;
    }

    public function stopGame() {
        $this->gameStarted = false;
    }

    #[On('game-finished')]
    public function gameFinished($gameFinished, $timeTaken, $moves) {
        $this->gameFinished = $gameFinished;
        $this->timeTaken = $timeTaken;
        $this->moves = $moves;
    }

    public function restartGame() {
        $this->gameFinished = false;
    }

    public function backToMainMenu() {
        $this->gameStarted = false;
        $this->gameFinished = false;
    }

    public function selectDifficulty(string $difficulty) {   
        $this->difficulty = $difficulty;
    }

    // Dummy
    public function loginUser() {
        if (auth()->check()) return;
        
        $user = User::first();
        auth()->login($user);
    }

    public function render() {
        return view('livewire.main-menu');
    }
}
