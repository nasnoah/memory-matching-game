<?php

use App\Models\Level;
use App\Models\Player;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Level::class)
                ->nullable()
                ->index()
                ->constrained((new Level)->getTable())
                ->nullOnDelete();
            $table->foreignIdFor(Player::class)
                ->nullable()
                ->index()
                ->constrained((new Player)->getTable())
                ->nullOnDelete();

            $table->json('game_items')->nullable();

            $table->string('status')->nullable()->comment('enum GameStatus');

            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            
            $table->decimal('time_taken')->nullable()/*->check('time_taken >= 0')*/;
            $table->integer('moves')->nullable();

            $table->bigInteger('scores')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
