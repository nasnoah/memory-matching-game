<?php

namespace App\Enums;

enum GameStatus: string {

    case STARTED    = 'started';
    case ENDED      = 'ended';
    case PAUSED     = 'paused';
    case STOPPED    = 'stopped';

    public function label() {
        return match ($this) {
            self::STARTED   => 'Started',
            self::ENDED     => 'Ended',
            self::PAUSED    => 'Paused',
            self::STOPPED   => 'Stopped',
            default         => null,
        };
    }
}