<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
use DefStudio\Telegraph\Facades\Telegraph;
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
        $userInfo = $this->chat->user_info;
        if(!$userInfo){
            $userInfo = UserInfo::create([
                'chat_id' => $this->chat->chat_id,
            ]);
        }
        if($userInfo->status < 9 ){
            TelegramUserInfoService::check_user_info($this->chat);
        }
        Telegraph::message('asdasd')->send();
    }
    private function handleCallbackQuery(): void
    {
        $data = $this->extractCallbackQueryData();
        Telegraph::message('call  '.json_encode($data))->send();
    }
    public function entering_lang(){
        $data = $this->extractCallbackQueryData();
        Telegraph::message('call  '.json_encode($data))->send();
        // $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        // $userInfo->language =
    }
}
