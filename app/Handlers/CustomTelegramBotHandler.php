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
        $userAction = $this->chat->user_action;
        if ($userAction) {
            switch ($userAction->screen) {
                case 'entering_weight':
                    $statusStore = TelegramUserInfoService::store_weight($this->chat, $text);
                    if ($statusStore) {
                        TelegramUserInfoService::check_user_info($this->chat);
                    }
                    break;
                case 'entering_goal_weight':
                    $statusStore = TelegramUserInfoService::store_goal_weight($this->chat, $text);
                    if ($statusStore) {
                        TelegramUserInfoService::check_user_info($this->chat);
                    }
                    break;
                case 'entering_tall':
                    $statusStore = TelegramUserInfoService::store_tall($this->chat, $text);
                    if ($statusStore) {
                        TelegramUserInfoService::check_user_info($this->chat);
                    }
                    break;
                case 'entering_age':
                    $statusStore = TelegramUserInfoService::store_age($this->chat, $text);
                    if ($statusStore) {
                        TelegramUserInfoService::check_user_info($this->chat);
                    }
                    break;
            }
        } else {
            Telegraph::message('xatolik')->send();
        }
    }
    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html("I can't understand your command: $text")->send();
    }
    public function start()
    {
        $bot = $this->chat->bot;
        $userInfo = $this->chat->user_info;
        if (!$userInfo) {
            $userInfo = UserInfo::create([
                'chat_id' => $this->chat->chat_id,
            ]);
        }
        if ($userInfo->status < 9) {
            TelegramUserInfoService::check_user_info($this->chat);
        } else {

            Telegraph::message('asdasd')->send();
        }
    }
    private function handleCallbackQuery(): void
    {
        $data = $this->extractCallbackQueryData();
        Telegraph::message('call  ' . json_encode($data))->send();
    }
    public function entering_lang()
    {
        $lang = $this->data->get('lang');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->language = $lang;
        $userInfo->status = 2;
        $userInfo->update();
        TelegramUserInfoService::check_user_info($this->chat);
    }
    public function entering_activity_type()
    {
        $activity_type_id = $this->data->get('activity_type_id');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->activity_type_id = $activity_type_id;
        $userInfo->status = 7;
        $userInfo->update();
        TelegramUserInfoService::check_user_info($this->chat);
    }
}
