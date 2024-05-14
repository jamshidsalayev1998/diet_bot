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

        $data = $request->all();
        $userInfo = null;
        if (key_exists('message', $data)) {
            $userInfo = UserInfo::where('chat_id', $data['message']['from']['id'])->orderBy('id', 'ASC')->first();
        } elseif (key_exists('callback_query', $data)) {
            $userInfo = UserInfo::where('chat_id', $data['callback_query']['from']['id'])->orderBy('id', 'ASC')->first();
        }
        if (!$userInfo) {
            $locale = config('app.locale', 'uz');
        } else {
            $locale = $userInfo->language;
        }
        app()->setLocale($locale);

        // TempMessage::create([
        //     'text_response' => json_encode($request->all())
        // ]);
    }
}
