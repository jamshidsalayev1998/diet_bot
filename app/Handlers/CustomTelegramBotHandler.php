<?php

namespace App\Handlers;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class CustomTelegramBotHandler extends WebhookHandler
{
    protected function handleChatMessage(Stringable $text): void
    {
        $this->chat->html("message : $text")->send();
    }
    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html("I can't understand your command: $text")->send();
    }
    public function start()
    {
        $bot = $this->chat->bot;
        $text = 'Bot ishlashni boshladi';
        $this->chat->html($text)->send();
    }
}
