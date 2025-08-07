<?php

use app\Http\Controllers\Admin\Event\EventController;
use app\Http\Controllers\Admin\EventSource\EventSourceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// settings.profile 라우트 추가
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')
        ->name('profile'); // 기존 profile 라우트 유지

    Route::view('settings/profile', 'settings.profile') // settings.profile 라우트 추가
        ->name('settings.profile');

    Route::view('settings/password', 'settings.password') // settings.password 라우트도 함께 추가 (일반적으로 같이 사용됨)
        ->name('settings.password');
});


// Admin 그룹 라우트
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', EventController::class);
    Route::resource('event-sources', EventSourceController::class);
    Route::post('event-sources/crawl', [EventSourceController::class, 'crawl'])->name('event-sources.crawl');
});

require __DIR__.'/auth.php';
