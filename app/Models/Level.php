<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model {

    use SoftDeletes;

    protected $fillable = [
        'name',
        'total_game_items',
    ];
    
    public function games(): HasMany {
        return $this->hasMany(Game::class);
    }
}
