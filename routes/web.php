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
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PostCategoryController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ApiKeyController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\BackupController;

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

        // Pages
        Route::get('/pages',                [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create',         [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages',               [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit',    [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}',         [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}',      [PageController::class, 'destroy'])->name('pages.destroy');

        // Menus
        Route::get('/menus',                                            [MenuController::class, 'index'])->name('menus.index');
        Route::post('/menus',                                           [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}',                                     [MenuController::class, 'show'])->name('menus.show');
        Route::put('/menus/{menu}',                                     [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}',                                  [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::post('/menus/{menu}/items',                              [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::put('/menus/{menu}/items/{item}',                        [MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('/menus/{menu}/items/{item}',                     [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
        Route::post('/menus/{menu}/items/{item}/move-up',               [MenuController::class, 'moveItemUp'])->name('menus.items.move-up');
        Route::post('/menus/{menu}/items/{item}/move-down',             [MenuController::class, 'moveItemDown'])->name('menus.items.move-down');
        Route::post('/menus/{menu}/reorder',                            [MenuController::class, 'reorderItems'])->name('menus.reorder');

        // Redirects
        Route::get('/redirects',                [RedirectController::class, 'index'])->name('redirects.index');
        Route::post('/redirects',               [RedirectController::class, 'store'])->name('redirects.store');
        Route::get('/redirects/{redirect}/edit',[RedirectController::class, 'edit'])->name('redirects.edit');
        Route::put('/redirects/{redirect}',     [RedirectController::class, 'update'])->name('redirects.update');
        Route::delete('/redirects/{redirect}',  [RedirectController::class, 'destroy'])->name('redirects.destroy');
        Route::post('/redirects/{redirect}/toggle', [RedirectController::class, 'toggle'])->name('redirects.toggle');
        Route::post('/redirects/import',        [RedirectController::class, 'import'])->name('redirects.import');

        // Blog — Posts
        Route::get('/posts',                  [PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/create',           [PostController::class, 'create'])->name('posts.create');
        Route::post('/posts',                 [PostController::class, 'store'])->name('posts.store');
        Route::get('/posts/{post}/edit',      [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/posts/{post}',           [PostController::class, 'update'])->name('posts.update');
        Route::delete('/posts/{post}',        [PostController::class, 'destroy'])->name('posts.destroy');
        Route::post('/posts/{post}/publish',  [PostController::class, 'publish'])->name('posts.publish');

        // Blog — Categories
        Route::get('/categories',             [PostCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories',            [PostCategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [PostCategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',  [PostCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [PostCategoryController::class, 'destroy'])->name('categories.destroy');

        // Leads (CRM)
        Route::get('/leads',                  [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/create',           [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads',                 [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/{lead}',           [LeadController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit',      [LeadController::class, 'edit'])->name('leads.edit');
        Route::put('/leads/{lead}',           [LeadController::class, 'update'])->name('leads.update');
        Route::delete('/leads/{lead}',        [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::post('/leads/{lead}/note',     [LeadController::class, 'addNote'])->name('leads.note');
        Route::post('/leads/{lead}/status',   [LeadController::class, 'updateStatus'])->name('leads.status');
        Route::get('/leads/export',           [LeadController::class, 'export'])->name('leads.export');

        // Subscribers
        Route::get('/subscribers',            [SubscriberController::class, 'index'])->name('subscribers.index');
        Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
        Route::post('/subscribers/bulk',      [SubscriberController::class, 'bulkAction'])->name('subscribers.bulk');
        Route::post('/subscribers/import',    [SubscriberController::class, 'import'])->name('subscribers.import');
        Route::get('/subscribers/export',     [SubscriberController::class, 'export'])->name('subscribers.export');

        // Email Campaigns
        Route::get('/campaigns',              [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create',       [CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns',             [CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}',   [CampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('/campaigns/{campaign}/send', [CampaignController::class, 'send'])->name('campaigns.send');

        // Support Tickets
        Route::get('/tickets',                [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}',       [TicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{ticket}/reply',[TicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
        Route::post('/tickets/{ticket}/priority', [TicketController::class, 'updatePriority'])->name('tickets.priority');
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
        Route::delete('/tickets/{ticket}',    [TicketController::class, 'destroy'])->name('tickets.destroy');

        // API Keys
        Route::get('/api-keys',               [ApiKeyController::class, 'index'])->name('api-keys.index');
        Route::post('/api-keys',              [ApiKeyController::class, 'store'])->name('api-keys.store');
        Route::delete('/api-keys/{apiKey}',   [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');
        Route::post('/api-keys/{apiKey}/toggle', [ApiKeyController::class, 'toggle'])->name('api-keys.toggle');

        // Notifications
        Route::get('/notifications',          [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}',  [NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Two-Factor Authentication
        Route::get('/two-factor',             [TwoFactorController::class, 'index'])->name('two-factor.index');
        Route::post('/two-factor/enable',     [TwoFactorController::class, 'enable'])->name('two-factor.enable');
        Route::post('/two-factor/disable',    [TwoFactorController::class, 'disable'])->name('two-factor.disable');
        Route::post('/two-factor/verify',     [TwoFactorController::class, 'verify'])->name('two-factor.verify');

        // System Health
        Route::get('/system/health',          [SystemHealthController::class, 'index'])->name('system.health');

        // Coupons
        Route::get('/coupons',                [CouponController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create',         [CouponController::class, 'create'])->name('coupons.create');
        Route::post('/coupons',               [CouponController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit',  [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}',       [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}',    [CouponController::class, 'destroy'])->name('coupons.destroy');

        // Backups
        Route::get('/backups',                [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups',               [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups/{backup}',    [BackupController::class, 'destroy'])->name('backups.destroy');
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
