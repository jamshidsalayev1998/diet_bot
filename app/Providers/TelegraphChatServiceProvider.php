<?php

namespace App\Providers;

use App\Models\V1\CalcAiConversation;
use App\Models\V1\UserInfo;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\ServiceProvider;

class TelegraphChatServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        TelegraphChat::macro('user_info', function() {
            return $this->hasOne(UserInfo::class);
        });
        TelegraphChat::macro('calc_ai_conversation', function() {
            return $this->hasOne(CalcAiConversation::class);
        });


    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
