<?php

namespace App\Handlers;

use DefStudio\Telegraph\Handlers\WebhookHandler;

class CustomTelegramBotHandler extends WebhookHandler
{
    public function start()
    {
        $bot = $this->chat->bot;
        $text = 'Bot ishlashni boshladi';
        $this->chat->html($text)->send();
    }
}
