<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use Illuminate\Support\Stringable;

class CustomTelegramBotHandler extends WebhookHandler
{
    use TelegramMessageLangsTrait;
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
            $this->chat->message('xatolik')->removeReplyKeyboard()->send();
        }
    }
    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html("I can't understand your command: $text")->send();
    }
    public function start()
    {

        $userInfo = $this->chat->user_info;
        $this->chat->message('Hush kelibsiz')->removeReplyKeyboard()->send();
        if (!$userInfo) {
            $userInfo = UserInfo::create([
                'chat_id' => $this->chat->chat_id,
            ]);
        }
        if ($userInfo->status < 9) {
            TelegramUserInfoService::check_user_info($this->chat, $userInfo);
        } else {
            $this->chat->message('asdasd')->send();
        }
    }
    private function handleCallbackQuery(): void
    {
        $data = $this->extractCallbackQueryData();
        $this->chat->message('call  ' . json_encode($data))->send();
    }
    public function entering_lang()
    {
        $lang = $this->data->get('lang');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->language = $lang;
        $userInfo->status = 2;
        $userInfo->update();
        app()->setLocale($lang);
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this::lang('language_selected'));
    }
    public function entering_activity_type()
    {
        $activity_type_id = $this->data->get('activity_type_id');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->activity_type_id = $activity_type_id;
        $userInfo->status = 8;
        $userInfo->update();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this::lang('activity_type_selected'));
    }
    public function selecting_gender()
    {
        $gender = $this->data->get('gender');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->gender = $gender;
        $userInfo->status = 3;
        $userInfo->update();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this::lang('gender_selected'));
    }
}
