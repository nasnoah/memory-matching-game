<?php

namespace App\Livewire;

use Livewire\Component;

class MainMenu extends Component {

    public bool $gameStarted;
    public bool $gameFinished;

    public string $difficulty; // easy, mid, hard

    public function mount() {
        $this->gameStarted = false;
        $this->gameFinished = false;

        $this->difficulty = 'easy';
    }

    public function startGame() {
        $this->gameStarted = true;
    }

    public function stopGame() {
        $this->gameStarted = false;
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

    public function render() {
        return view('livewire.main-menu');
    }
}
