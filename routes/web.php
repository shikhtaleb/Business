<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ProfileController;

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

    // Authenticated admin routes
    Route::middleware('admin.auth')->group(function () {

        Route::get('/',          [DashboardController::class, 'index'])->name('dashboard');

        // Content
        Route::get('/content',   [ContentController::class, 'index'])->name('content.index');
        Route::post('/content',  [ContentController::class, 'save'])->name('content.save');

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
        Route::get('/roles',             [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles',            [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}',      [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}',   [RoleController::class, 'destroy'])->name('roles.destroy');

        // Analytics
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    });
});

// ── Frontend ──────────────────────────────────────────────────────────────────
Route::middleware(['check.installed.done', 'track.pageview'])->group(function () {
    Route::get('/', [FrontendController::class, 'index'])->name('home');
});
