<?php

namespace App\Providers;

use App\Models\TempMessage;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class LangServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Request $request): void
    {
        TempMessage::create([
            'text_response' => json_encode($request->all())
        ]);
        // $locale = session('locale', config('app.locale'));
        // app()->setLocale($locale);
    }
}
