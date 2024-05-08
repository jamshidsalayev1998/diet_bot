<?php

use DefStudio\Telegraph\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

if ($webhookUrl = config('telegraph.webhook_url', config('telegraph.webhook.url', '/telegraph/{token}/webhook'))) {

    Route::post($webhookUrl, [WebhookController::class, 'handle'])
        ->middleware(config('telegraph.webhook.middleware', []))
        ->name('telegraph.webhook');

    Route::post($webhookUrl.'?6502974186:AAHY3T5E9jkXNre7aqZ0ShvNt25x23mC0DU', [WebhookController::class, 'handle'])
        ->middleware(config('telegraph.webhook.middleware', []))
        ->name('telegraph.webhook');
}

