<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->json('builder_content')->nullable()->after('page_text');
            $table->longText('builder_html')->nullable()->after('builder_content');
            $table->text('builder_css')->nullable()->after('builder_html');
            $table->boolean('use_builder')->default(false)->after('builder_css');
        });
    }

    public function down(): void
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->dropColumn(['slug', 'builder_content', 'builder_html', 'builder_css', 'use_builder']);
        });
    }
};
