<?php

namespace App\Livewire;

use Livewire\Component;

class MainMenu extends Component
{
    public bool $isStarting;
    public bool $hasEnded;

    public string $difficulty; // easy, mid, hard

    public function mount() 
    {
        $this->isStarting = false;
        $this->hasEnded = false;

        $this->difficulty = 'easy';
    }

    public function startGame() 
    {
        $this->isStarting = true;
    }

    public function stopGame()
    {
        $this->isStarting = false;
    }

    public function restartGame()
    {
        $this->hasEnded = false;
    }

    public function backToMainMenu() 
    {
        $this->isStarting = false;
        $this->hasEnded = false;
    }

    public function selectDifficulty(string $difficulty) 
    {   
        $this->difficulty = $difficulty;
    }

    public function render()
    {
        return view('livewire.main-menu');
    }
}
