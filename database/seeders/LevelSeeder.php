<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = collect(range(1, 10))->map(fn ($map) => ['name' => (string)$map, 'total_game_items' => 4 + 2 * $map]);

        $levels->each(fn ($each) => Level::create($each));
    }
}
