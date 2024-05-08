<?php

namespace App\Handlers;

use App\Models\TempMessage;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class CustomTelegramBotHandler extends WebhookHandler
{
    protected function handleChatMessage(Stringable $text): void
    {
        TempMessage::create(['text_response' => 'chat message ga galdi']);
        $this->chat->html("message : $text")->send();
    }
    protected function handleUnknownCommand(Stringable $text): void
    {
        TempMessage::create(['text_response' => 'unknown a galdi']);
        $this->chat->html("I can't understand your command: $text")->send();
    }
    public function start()
    {
        TempMessage::create(['text_response' => 'starta galdi']);
        $bot = $this->chat->bot;
        $text = 'Bot ishlashni boshladi';
        $this->chat->html($text)->send();
    }
}
