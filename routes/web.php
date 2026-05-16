<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\LanguageManagerController;

// ── Installation wizard ───────────────────────────────────────────────────────
Route::prefix('install')->name('install.')->middleware('check.installed')->group(function () {
    Route::get('/',                   [InstallController::class, 'index'])->name('index');
    Route::post('/test-connection',   [InstallController::class, 'testConnection'])->name('test-connection');
    Route::post('/run',               [InstallController::class, 'install'])->name('run');
});

// ── Admin panel ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware('check.installed.done')->group(function () {

    // Auth (unauthenticated users)
    Route::get('/login',                  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',                 [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout',                [AuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password',        [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password',       [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'resetPassword'])->name('password.update');

    // Admin language switcher (no locale middleware needed)
    Route::post('/language', function (\Illuminate\Http\Request $request) {
        $lang = $request->input('lang', 'en');
        if (in_array($lang, ['ar', 'en', 'nl', 'de'])) {
            session(['admin_lang' => $lang]);
        }
        return back();
    })->name('language');

    // Authenticated admin routes
    Route::middleware(['admin.auth', 'admin.locale'])->group(function () {

        Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');

        // Content
        Route::get('/content',                        [ContentController::class, 'index'])->name('content.index');
        Route::post('/content',                       [ContentController::class, 'save'])->name('content.save');
        Route::get('/content/export/{lang}',          [ContentController::class, 'export'])->name('content.export');
        Route::post('/content/import',                [ContentController::class, 'import'])->name('content.import');

        // Media
        Route::get('/media',             [MediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload',     [MediaController::class, 'upload'])->name('media.upload');
        Route::delete('/media/{id}',     [MediaController::class, 'destroy'])->name('media.destroy');

        // Settings
        Route::get('/settings/appearance',  [SettingsController::class, 'appearance'])->name('settings.appearance');
        Route::post('/settings/appearance', [SettingsController::class, 'saveAppearance'])->name('settings.appearance.save');
        Route::get('/settings/seo',         [SettingsController::class, 'seo'])->name('settings.seo');
        Route::post('/settings/seo',        [SettingsController::class, 'saveSeo'])->name('settings.seo.save');
        Route::get('/settings/general',     [SettingsController::class, 'general'])->name('settings.general');
        Route::post('/settings/general',    [SettingsController::class, 'saveGeneral'])->name('settings.general.save');

        // Users
        Route::get('/users',              [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',       [UserController::class, 'create'])->name('users.create');
        Route::post('/users',             [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',  [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',       [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',    [UserController::class, 'destroy'])->name('users.destroy');

        // Profile
        Route::get('/profile',          [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile',         [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password',[ProfileController::class, 'updatePassword'])->name('profile.password');

        // Roles
        Route::get('/roles',              [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create',       [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles',             [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit',  [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}',       [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}',    [RoleController::class, 'destroy'])->name('roles.destroy');

        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

        // Activity Log
        Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

        // SMTP settings
        Route::get('/settings/smtp',       [SettingsController::class, 'smtp'])->name('settings.smtp');
        Route::post('/settings/smtp',      [SettingsController::class, 'saveSmtp'])->name('settings.smtp.save');
        Route::post('/settings/smtp/test', [SettingsController::class, 'testSmtp'])->name('settings.smtp.test');

        // Cache management
        Route::post('/settings/cache/clear', [SettingsController::class, 'clearCache'])->name('settings.cache.clear');

        // Maintenance mode toggle (AJAX)
        Route::post('/settings/maintenance', [SettingsController::class, 'toggleMaintenance'])->name('settings.maintenance');

        // Messages / Inbox
        Route::get('/messages',                     [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}',           [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/reply',    [MessageController::class, 'reply'])->name('messages.reply');
        Route::delete('/messages/{message}',        [MessageController::class, 'destroy'])->name('messages.destroy');
        Route::post('/messages/bulk',               [MessageController::class, 'bulkAction'])->name('messages.bulk');

        // Plans & Pricing
        Route::get('/plans',             [PlanController::class, 'index'])->name('plans.index');
        Route::get('/plans/create',      [PlanController::class, 'create'])->name('plans.create');
        Route::post('/plans',            [PlanController::class, 'store'])->name('plans.store');
        Route::get('/plans/{plan}/edit', [PlanController::class, 'edit'])->name('plans.edit');
        Route::put('/plans/{plan}',      [PlanController::class, 'update'])->name('plans.update');
        Route::delete('/plans/{plan}',   [PlanController::class, 'destroy'])->name('plans.destroy');
        Route::post('/plans/reorder',    [PlanController::class, 'reorder'])->name('plans.reorder');

        // Language Manager
        Route::get('/languages',                              [LanguageManagerController::class, 'index'])->name('languages.index');
        Route::get('/languages/create',                       [LanguageManagerController::class, 'create'])->name('languages.create');
        Route::post('/languages',                             [LanguageManagerController::class, 'store'])->name('languages.store');
        Route::get('/languages/{language}/edit',              [LanguageManagerController::class, 'edit'])->name('languages.edit');
        Route::put('/languages/{language}',                   [LanguageManagerController::class, 'update'])->name('languages.update');
        Route::delete('/languages/{language}',                [LanguageManagerController::class, 'destroy'])->name('languages.destroy');
        Route::get('/languages/{language}/export',            [LanguageManagerController::class, 'exportTranslations'])->name('languages.export');
    });
});

// ── Frontend ──────────────────────────────────────────────────────────────────
Route::middleware(['check.installed.done', 'maintenance.mode', 'track.pageview'])->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('home');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
});

// SEO files (no maintenance mode or pageview tracking needed)
Route::get('/sitemap.xml', [FrontendController::class, 'sitemap'])->middleware('check.installed.done');
Route::get('/robots.txt',  [FrontendController::class, 'robots'])->middleware('check.installed.done');
