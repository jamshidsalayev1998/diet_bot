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

Route::post('/telegraph/6502974186:AAHY3T5E9jkXNre7aqZ0ShvNt25x23mC0DU/webhook/{bot}' , function($bot){
    TempMessage::create([
        'text_response' => json_encode($bot)
    ]);
})->name('telegraph.webhook');

