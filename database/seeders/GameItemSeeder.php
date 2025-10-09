<?php

namespace Database\Seeders;

use App\Models\GameItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gameItems = [
            [
                'name'      => 'Air',
                'content'   => null, 
            ],
            [
                'name'      => 'Bicycle',
                'content'   => null, 
            ],
            [
                'name'      => 'Cat',
                'content'   => null, 
            ],
            [
                'name'      => 'Dinasour',
                'content'   => null, 
            ],
            [
                'name'      => 'Elephant',
                'content'   => null, 
            ],
            [
                'name'      => 'Fan',
                'content'   => null, 
            ],
            [
                'name'      => 'Grease',
                'content'   => null, 
            ],
            [
                'name'      => 'Horse',
                'content'   => null, 
            ],
            [
                'name'      => 'Internet',
                'content'   => null, 
            ],
            [
                'name'      => 'Joker',
                'content'   => null, 
            ],
            [
                'name'      => 'King',
                'content'   => null, 
            ],
            [
                'name'      => 'Language',
                'content'   => null, 
            ],
        ];

        foreach ($gameItems as $gameItem) {
            GameItem::create([
                'name'      => $gameItem['name'],
                'content'   => $gameItem['content'],
            ]);
        }
    }
}
