<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\FeatureFlagController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ContactCompanyController;
use App\Http\Controllers\Admin\ContactContactController;
use App\Http\Controllers\Admin\ContentCategoryController;
use App\Http\Controllers\Admin\ContentPageController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Admin\ContentTagController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CrmCustomerController;
use App\Http\Controllers\Admin\CrmDocumentController;
use App\Http\Controllers\Admin\CrmNoteController;
use App\Http\Controllers\Admin\CrmStatusController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqQuestionController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductTagController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionOptionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemCalendarController;
use App\Http\Controllers\Admin\TaskCalendarController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\TaskStatusController;
use App\Http\Controllers\Admin\TaskTagController;
use App\Http\Controllers\Admin\TestAnswerController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\TestResultController;
use App\Http\Controllers\Admin\UserAlertController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ApprovalController;
use App\Http\Controllers\Auth\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Auth::routes(['verify' => true]);

Route::get('email/approval', [ApprovalController::class, 'show'])->name('approval.notice');

// 2FA challenge — avant auth complète
Route::get('/2fa/challenge',  [TwoFactorController::class, 'challenge'])->name('2fa.challenge')->middleware('auth');
Route::post('/2fa/verify',    [TwoFactorController::class, 'verify'])->name('2fa.verify')->middleware('auth');

Route::group(['middleware' => ['auth', 'verified', 'approved', 'two-factor']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // 2FA setup/disable
    Route::get('/2fa/setup',   [TwoFactorController::class, 'setup'])->name('2fa.setup');
    Route::post('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable',[TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Permissions
    Route::resource('permissions', PermissionController::class, ['except' => ['store', 'update', 'destroy']]);

    // Roles
    Route::resource('roles', RoleController::class, ['except' => ['store', 'update', 'destroy']]);

    // Users
    Route::resource('users', UserController::class, ['except' => ['store', 'update', 'destroy']]);

    // Audit Logs
    Route::resource('audit-logs', AuditLogController::class, ['except' => ['store', 'update', 'destroy', 'create', 'edit']]);

    // User Alert
    Route::get('user-alerts/seen', [UserAlertController::class, 'seen'])->name('user-alerts.seen');
    Route::resource('user-alerts', UserAlertController::class, ['except' => ['store', 'update', 'destroy']]);

    // Product Category
    Route::post('product-categories/media', [ProductCategoryController::class, 'storeMedia'])->name('product-categories.storeMedia');
    Route::resource('product-categories', ProductCategoryController::class, ['except' => ['store', 'update', 'destroy']]);

    // Product Tag
    Route::resource('product-tags', ProductTagController::class, ['except' => ['store', 'update', 'destroy']]);

    // Product
    Route::post('products/media', [ProductController::class, 'storeMedia'])->name('products.storeMedia');
    Route::resource('products', ProductController::class, ['except' => ['store', 'update', 'destroy']]);

    // System Calendar
    Route::resource('system-calendars', SystemCalendarController::class, ['except' => ['store', 'update', 'destroy', 'create', 'edit', 'show']]);

    // Content Category
    Route::resource('content-categories', ContentCategoryController::class, ['except' => ['store', 'update', 'destroy']]);

    // Content Tag
    Route::resource('content-tags', ContentTagController::class, ['except' => ['store', 'update', 'destroy']]);

    // Content Page
    Route::post('content-pages/media', [ContentPageController::class, 'storeMedia'])->name('content-pages.storeMedia');
    Route::resource('content-pages', ContentPageController::class, ['except' => ['store', 'update', 'destroy']]);

    // Page Builder
    Route::prefix('page-builder')->name('page-builder.')->group(function () {
        Route::get('{contentPage}/edit',       [PageBuilderController::class, 'edit'])->name('edit');
        Route::post('{contentPage}/save',      [PageBuilderController::class, 'save'])->name('save');
        Route::get('{contentPage}/preview',    [PageBuilderController::class, 'preview'])->name('preview');
        Route::post('{contentPage}/media',     [PageBuilderController::class, 'uploadMedia'])->name('media');
    });

    // Crm Status
    Route::resource('crm-statuses', CrmStatusController::class, ['except' => ['store', 'update', 'destroy']]);

    // Crm Customer
    Route::resource('crm-customers', CrmCustomerController::class, ['except' => ['store', 'update', 'destroy']]);

    // Crm Note
    Route::resource('crm-notes', CrmNoteController::class, ['except' => ['store', 'update', 'destroy']]);

    // Crm Document
    Route::post('crm-documents/media', [CrmDocumentController::class, 'storeMedia'])->name('crm-documents.storeMedia');
    Route::resource('crm-documents', CrmDocumentController::class, ['except' => ['store', 'update', 'destroy']]);

    // Task Status
    Route::resource('task-statuses', TaskStatusController::class, ['except' => ['store', 'update', 'destroy']]);

    // Task Tag
    Route::resource('task-tags', TaskTagController::class, ['except' => ['store', 'update', 'destroy']]);

    // Task
    Route::post('tasks/media', [TaskController::class, 'storeMedia'])->name('tasks.storeMedia');
    Route::resource('tasks', TaskController::class, ['except' => ['store', 'update', 'destroy']]);

    // Task Calendar
    Route::resource('task-calendars', TaskCalendarController::class, ['except' => ['store', 'update', 'destroy', 'create', 'edit', 'show']]);

    // Faq Category
    Route::resource('faq-categories', FaqCategoryController::class, ['except' => ['store', 'update', 'destroy']]);

    // Faq Question
    Route::resource('faq-questions', FaqQuestionController::class, ['except' => ['store', 'update', 'destroy']]);

    // Contact Company
    Route::resource('contact-companies', ContactCompanyController::class, ['except' => ['store', 'update', 'destroy']]);

    // Contact Contacts
    Route::resource('contact-contacts', ContactContactController::class, ['except' => ['store', 'update', 'destroy']]);

    // Courses
    Route::post('courses/media', [CourseController::class, 'storeMedia'])->name('courses.storeMedia');
    Route::resource('courses', CourseController::class, ['except' => ['store', 'update', 'destroy']]);

    // Lessons
    Route::post('lessons/media', [LessonController::class, 'storeMedia'])->name('lessons.storeMedia');
    Route::resource('lessons', LessonController::class, ['except' => ['store', 'update', 'destroy']]);

    // Tests
    Route::resource('tests', TestController::class, ['except' => ['store', 'update', 'destroy']]);

    // Questions
    Route::post('questions/media', [QuestionController::class, 'storeMedia'])->name('questions.storeMedia');
    Route::resource('questions', QuestionController::class, ['except' => ['store', 'update', 'destroy']]);

    // Question Options
    Route::resource('question-options', QuestionOptionController::class, ['except' => ['store', 'update', 'destroy']]);

    // Test Results
    Route::resource('test-results', TestResultController::class, ['except' => ['store', 'update', 'destroy']]);

    // Test Answers
    Route::resource('test-answers', TestAnswerController::class, ['except' => ['store', 'update', 'destroy']]);

    // Messages
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('messages/inbox', [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('messages/outbox', [MessageController::class, 'outbox'])->name('messages.outbox');
    Route::get('messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::get('messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{conversation}', [MessageController::class, 'update'])->name('messages.update');
    Route::post('messages/{conversation}/destroy', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Feature Flags
    Route::prefix('feature-flags')->name('feature-flags.')->group(function () {
        Route::get('/',             [FeatureFlagController::class, 'index'])->name('index');
        Route::get('/audit',        [FeatureFlagController::class, 'audit'])->name('audit');
        Route::post('{key}/toggle', [FeatureFlagController::class, 'toggle'])->name('toggle');
        Route::post('sync',         [FeatureFlagController::class, 'sync'])->name('sync');
    });

    // Paramètres généraux
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/',      [SettingsController::class, 'index'])->name('index');
        Route::post('/',     [SettingsController::class, 'update'])->name('update');
        Route::post('theme', [SettingsController::class, 'updateTheme'])->name('theme');
    });
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    if (file_exists(app_path('Http/Controllers/Auth/UserProfileController.php'))) {
        Route::get('/', [UserProfileController::class, 'show'])->name('show');
    }
});
