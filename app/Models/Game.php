<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model {

    use SoftDeletes;

    protected $fillable = [
        'level_id',
        'player_id',
        'game_items',
        'status',
        'start_at',
        'end_at',
        'time_taken',
        'moves',
        'scores',
    ];

    protected $attributes = [
        'game_items'    => '[]',
    ];

    protected $casts = [
        'game_items'    => 'array',
        'start_at'      => 'datetime',
        'end_at'        => 'datetime', 
    ];

    public function level(): BelongsTo {
        return $this->belongsTo(Level::class);
    }

    public function player(): BelongsTo {
        return $this->belongsTo(Player::class);
    }
}
