<?php

use App\Models\TempMessage;
use DefStudio\Telegraph\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

if ($webhookUrl = config('telegraph.webhook_url', config('telegraph.webhook.url', '/telegraph/{token}/webhook'))) {

    Route::post($webhookUrl, function(){
        return \response()->noContent();
    })
        ->middleware(config('telegraph.webhook.middleware', []))
        ->name('telegraph.webhook');

}

