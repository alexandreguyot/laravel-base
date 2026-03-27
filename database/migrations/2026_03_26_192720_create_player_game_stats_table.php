<?php

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
        Schema::create('player_game_stats', function (Blueprint $table) {
            $table->id();
            $table->date('game_date')->index();
            $table->unsignedBigInteger('player_api_id');
            $table->string('player_name');
            $table->string('team');
            $table->string('team_abbreviation')->nullable();
            $table->string('team_logo')->nullable();
            $table->string('conference')->nullable();
            $table->string('division')->nullable();
            $table->string('position')->nullable();
            $table->string('country')->nullable();
            $table->string('height')->nullable();
            $table->string('headshot')->nullable();
            $table->integer('jersey_number')->nullable();
            $table->integer('pts')->default(0);
            $table->integer('reb')->default(0);
            $table->integer('ast')->default(0);
            $table->integer('eval')->default(0);
            $table->integer('stl')->default(0);
            $table->integer('blk')->default(0);
            $table->string('min')->nullable();
            $table->integer('fgm')->default(0);
            $table->integer('fga')->default(0);
            $table->integer('fg3m')->default(0);
            $table->integer('fg3a')->default(0);
            $table->integer('ftm')->default(0);
            $table->integer('fta')->default(0);
            $table->integer('turnover')->default(0);
            $table->timestamps();

            $table->unique(['game_date', 'player_api_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_game_stats');
    }
};
