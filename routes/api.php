<?php

use app\Http\Controllers\Admin\Event\EventController;
use app\Http\Controllers\Admin\EventSource\EventSourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/events', [EventController::class, 'storeFromCrawler']);
Route::get('/event-sources', [EventSourceController::class, 'indexForCrawler']);
