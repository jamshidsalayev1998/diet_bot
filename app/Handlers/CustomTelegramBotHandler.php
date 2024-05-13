<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\TelegramUserInfoService;
use App\Services\UserActionService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
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
            $keyboard = ReplyKeyboard::make()
                ->row([
                    ReplyButton::make($this->lang('menu')),
                ])->resize()
                ->row([
                    ReplyButton::make($this->lang('settings')),
                    ReplyButton::make($this->lang('support')),
                ])->resize();
            $this->chat->message('your_user_info_stored')->replyKeyboard($keyboard)->send();
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

    public function start_again_user_info()
    {
        $userInfo = $this->chat->user_info;
        $userInfo->status = 2;
        $userInfo->update();
        UserActionService::add($this->chat, 'selecting_gender');
        TelegramUserInfoService::check_user_info($this->chat, $userInfo);
        $this->reply($this::lang('started_again_user_info'));
    }
    public function confirm_user_info()
    {
        $userInfo = $this->chat->user_info;
        $userInfo->status = 11;
        $userInfo->update();
        $this->chat->message('your_user_info_stored')->replyKeyboard([
            ReplyKeyboard::make()->button('adfsf')->resize(),
            ReplyKeyboard::make()->button('adfsf')->resize()
        ])->send();
        $this->reply($this::lang('user_info_confirmed'));
    }
}
