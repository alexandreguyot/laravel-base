<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Clé unique ex: crm, lms_tests');
            $table->boolean('enabled')->default(true);
            $table->string('label')->nullable()->comment('Surcharge du label config');
            $table->text('description')->nullable()->comment('Surcharge de la description config');
            $table->string('group')->nullable()->comment('Groupe d\'affichage dans l\'UI');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
