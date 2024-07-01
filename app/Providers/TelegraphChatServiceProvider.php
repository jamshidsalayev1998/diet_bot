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



    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        TelegraphChat::macro('user_info', function() {
            return $this->hasOne(UserInfo::class , 'chat_id' , 'chat_id');
        });
        TelegraphChat::macro('calc_ai_conversation', function() {
            return $this->hasOne(CalcAiConversation::class , 'chat_id' , 'chat_id');
        });
    }
}
