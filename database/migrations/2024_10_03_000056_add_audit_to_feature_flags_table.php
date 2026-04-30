<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feature_flags', function (Blueprint $table) {
            $table->foreignId('last_changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_changed_at')->nullable();
        });

        Schema::create('feature_flag_audits', function (Blueprint $table) {
            $table->id();
            $table->string('feature_key');
            $table->boolean('old_value')->nullable();
            $table->boolean('new_value');
            $table->string('source')->default('ui')->comment('ui | artisan | api');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable()->comment('Snapshot du nom au moment du changement');
            $table->timestamps();

            $table->index('feature_key');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flag_audits');
        Schema::table('feature_flags', function (Blueprint $table) {
            $table->dropForeign(['last_changed_by']);
            $table->dropColumn(['last_changed_by', 'last_changed_at']);
        });
    }
};
