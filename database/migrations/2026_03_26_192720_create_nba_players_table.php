<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nba_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique(); // ID balldontlie
            $table->unsignedBigInteger('espn_id')->nullable();
            $table->string('full_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('headshot_url')->nullable();
            $table->string('team_abbreviation')->nullable();
            $table->string('team_logo')->nullable();
            $table->string('position')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nba_players');
    }
};
