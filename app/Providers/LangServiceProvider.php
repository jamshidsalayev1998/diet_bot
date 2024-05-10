<?php

namespace App\Providers;

use App\Models\TempMessage;
use App\Models\V1\UserInfo;
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
        $data = $request->all();
        $userInfo = null;
        if (key_exists('message', $data))
            $userInfo = UserInfo::where('chat_id', $data['message']['from']['id'])->orderBy('id', 'ASC')->first();
        if (!$userInfo) {
            $locale = config('app.locale');
        } else {
            $locale = $userInfo->language;
        }
        app()->setLocale($locale);
    }
}
