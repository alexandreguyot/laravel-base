<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            // Pivot tables first (foreign key constraints)
            'task_task_tag', 'course_user', 'content_category_content_page',
            'content_page_content_tag', 'product_product_tag', 'product_product_category',
            'user_user_alert', 'permission_role', 'role_user',
            // Main tables
            'im_recipients', 'im_messages', 'im_conversations',
            'test_answers', 'test_results', 'question_options', 'questions', 'tests',
            'faq_questions', 'faq_categories',
            'lessons', 'courses',
            'contact_contacts', 'contact_companies',
            'crm_documents', 'crm_notes', 'crm_customers', 'crm_statuses',
            'task_tags', 'task_statuses', 'tasks',
            'content_tags', 'content_pages', 'content_categories',
            'product_tags', 'product_categories', 'products',
            'user_alerts', 'audit_logs', 'media',
            'permissions', 'roles',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // pas de rollback
    }
};
