<?php

namespace App\Providers;

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
    public function boot(): void
    {
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
    }
}
