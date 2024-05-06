<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use DefStudio\Telegraph\Controllers\WebhookController;

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
if ($webhookUrl = config('telegraph.webhook.url')) {

    Route::post($webhookUrl, [WebhookController::class, 'handle'])
        ->middleware(config('telegraph.webhook.middleware', []))
        ->name('telegraph.webhook');

}
