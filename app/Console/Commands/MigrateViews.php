<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateViews extends Command
{
    protected $signature   = 'views:migrate-to-daisy {--dry-run : Affiche les changements sans écrire}';
    protected $description = 'Migre les vues admin CRUD vers le style DaisyUI';

    /**
     * Mapping : dossier admin → [trans_key, permission_prefix, route_prefix, livewire_component]
     * null = pas de bouton "Créer"
     */
    protected array $modules = [
        'audit-log'        => ['cruds.auditLog',         null,               'audit-logs',         'audit-log.index'],
        'contact-company'  => ['cruds.contactCompany',   'contact_company',  'contact-companies',  'contact-company.index'],
        'contact-contact'  => ['cruds.contactContact',   'contact_contact',  'contact-contacts',   'contact-contact.index'],
        'content-category' => ['cruds.contentCategory',  'content_category', 'content-categories', 'content-category.index'],
        'content-page'     => ['cruds.contentPage',      'content_page',     'content-pages',      'content-page.index'],
        'content-tag'      => ['cruds.contentTag',       'content_tag',      'content-tags',       'content-tag.index'],
        'course'           => ['cruds.course',           'course',           'courses',            'course.index'],
        'crm-customer'     => ['cruds.crmCustomer',      'crm_customer',     'crm-customers',      'crm-customer.index'],
        'crm-document'     => ['cruds.crmDocument',      'crm_document',     'crm-documents',      'crm-document.index'],
        'crm-note'         => ['cruds.crmNote',          'crm_note',         'crm-notes',          'crm-note.index'],
        'crm-status'       => ['cruds.crmStatus',        'crm_status',       'crm-statuses',       'crm-status.index'],
        'faq-category'     => ['cruds.faqCategory',      'faq_category',     'faq-categories',     'faq-category.index'],
        'faq-question'     => ['cruds.faqQuestion',      'faq_question',     'faq-questions',      'faq-question.index'],
        'lesson'           => ['cruds.lesson',           'lesson',           'lessons',            'lesson.index'],
        'permission'       => ['cruds.permission',       null,               'permissions',        'permission.index'],
        'product-category' => ['cruds.productCategory',  'product_category', 'product-categories', 'product-category.index'],
        'product-tag'      => ['cruds.productTag',       'product_tag',      'product-tags',       'product-tag.index'],
        'question'         => ['cruds.question',         'question',         'questions',          'question.index'],
        'question-option'  => ['cruds.questionOption',   'question_option',  'question-options',   'question-option.index'],
        'role'             => ['cruds.role',             null,               'roles',              'role.index'],
        'task'             => ['cruds.task',             'task',             'tasks',              'task.index'],
        'task-status'      => ['cruds.taskStatus',       'task_status',      'task-statuses',      'task-status.index'],
        'task-tag'         => ['cruds.taskTag',          'task_tag',         'task-tags',          'task-tag.index'],
        'test'             => ['cruds.test',             'test',             'tests',              'test.index'],
        'test-answer'      => ['cruds.testAnswer',       'test_answer',      'test-answers',       'test-answer.index'],
        'test-result'      => ['cruds.testResult',       'test_result',      'test-results',       'test-result.index'],
        'user'             => ['cruds.user',             null,               'users',              'user.index'],
        'user-alert'       => ['cruds.userAlert',        'user_alert',       'user-alerts',        'user-alert.index'],
    ];

    public function handle(): int
    {
        $dry   = $this->option('dry-run');
        $base  = resource_path('views/admin');
        $count = 0;

        foreach ($this->modules as $folder => [$trans, $perm, $route, $livewireBase]) {
            // Le nom de variable PHP (ex: crm-customer → $crmCustomer)
            $varName  = Str::camel($folder);
            // Livewire sans .index
            $lwBase   = Str::beforeLast($livewireBase, '.index');

            // ── index ──────────────────────────────────────────────
            $indexPath = "{$base}/{$folder}/index.blade.php";
            if (file_exists($indexPath)) {
                $createBlock = $perm
                    ? "@can('{$perm}_create')\n            <x-ui.button href=\"{{ route('{$route}.create') }}\" icon=\"plus\">\n                {{ trans('global.add') }} {{ trans('{$trans}.title_singular') }}\n            </x-ui.button>\n        @endcan"
                    : '';

                $content = "@extends('layouts.admin')\n@section('content')\n\n<div class=\"space-y-4\">\n    <x-ui.breadcrumb :items=\"[\n        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],\n        ['label' => trans('{$trans}.title')],\n    ]\" />\n\n    <div class=\"flex items-center justify-between\">\n        <h1 class=\"text-2xl font-bold\">{{ trans('{$trans}.title') }}</h1>\n        {$createBlock}\n    </div>\n\n    <x-ui.card>\n        @livewire('{$livewireBase}')\n    </x-ui.card>\n</div>\n\n@endsection\n";

                $this->writeFile($indexPath, $content, "admin/{$folder}/index.blade.php", $dry);
                $count++;
            }

            // ── create ─────────────────────────────────────────────
            $createPath = "{$base}/{$folder}/create.blade.php";
            if (file_exists($createPath) && $perm) {
                $content = "@extends('layouts.admin')\n@section('content')\n\n<div class=\"space-y-4\">\n    <x-ui.breadcrumb :items=\"[\n        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],\n        ['label' => trans('{$trans}.title'), 'href' => route('{$route}.index')],\n        ['label' => trans('global.create')],\n    ]\" />\n\n    <div class=\"flex items-center justify-between\">\n        <h1 class=\"text-2xl font-bold\">{{ trans('global.create') }} {{ trans('{$trans}.title_singular') }}</h1>\n        <x-ui.button href=\"{{ route('{$route}.index') }}\" variant=\"ghost\" icon=\"arrow-left\">\n            {{ trans('global.back_to_list') }}\n        </x-ui.button>\n    </div>\n\n    <x-ui.card>\n        @livewire('{$lwBase}.create')\n    </x-ui.card>\n</div>\n\n@endsection\n";

                $this->writeFile($createPath, $content, "admin/{$folder}/create.blade.php", $dry);
                $count++;
            }

            // ── edit ───────────────────────────────────────────────
            $editPath = "{$base}/{$folder}/edit.blade.php";
            if (file_exists($editPath) && $perm) {
                $content = "@extends('layouts.admin')\n@section('content')\n\n<div class=\"space-y-4\">\n    <x-ui.breadcrumb :items=\"[\n        ['label' => trans('global.dashboard'), 'href' => route('home'), 'icon' => 'layout-dashboard'],\n        ['label' => trans('{$trans}.title'), 'href' => route('{$route}.index')],\n        ['label' => trans('global.edit')],\n    ]\" />\n\n    <div class=\"flex items-center justify-between\">\n        <h1 class=\"text-2xl font-bold\">\n            {{ trans('global.edit') }} {{ trans('{$trans}.title_singular') }}\n            <span class=\"text-base-content/40 font-normal text-lg\">#{{ \${$varName}->id }}</span>\n        </h1>\n        <x-ui.button href=\"{{ route('{$route}.index') }}\" variant=\"ghost\" icon=\"arrow-left\">\n            {{ trans('global.back_to_list') }}\n        </x-ui.button>\n    </div>\n\n    <x-ui.card>\n        @livewire('{$lwBase}.edit', [\${$varName}])\n    </x-ui.card>\n</div>\n\n@endsection\n";

                $this->writeFile($editPath, $content, "admin/{$folder}/edit.blade.php", $dry);
                $count++;
            }
        }

        $action = $dry ? 'analysés' : 'migrés';
        $this->info("\n{$count} fichiers {$action}.");

        return self::SUCCESS;
    }

    protected function writeFile(string $path, string $content, string $label, bool $dry): void
    {
        if ($dry) {
            $this->line("<fg=cyan>{$label}</> — serait réécrit");
        } else {
            file_put_contents($path, $content);
            $this->line("<fg=green>✓</> {$label}");
        }
    }
}
