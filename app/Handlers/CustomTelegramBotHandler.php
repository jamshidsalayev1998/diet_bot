<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
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
        $childChat = ChildTelegramChat::where('chat_id',$this->chat->chat_id)->first();
        $userInfo = $childChat->user_info;
        if (!$userInfo) {
            $userInfo = UserInfo::create([
                'chat_id' => $this->chat->chat_id,
            ]);
        }
        if ($userInfo->status < 9) {
            TelegramUserInfoService::check_user_info($childChat);
        }
        $text = 'Bot ishlashni boshladi hihihi';
        $this->chat->html($text)->send();
    }
}
