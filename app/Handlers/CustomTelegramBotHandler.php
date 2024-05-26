<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\MenuImageGeneratorService;
use App\Services\TelegramButtonService;
use App\Services\TelegramUserInfoService;
use App\Services\UserActionService;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Stringable;

class CustomTelegramBotHandler extends WebhookHandler
{
    use TelegramMessageLangsTrait;
    protected function handleChatMessage(Stringable $text): void
    {
        $userAction = $this->chat->user_action;
        $userInfo = $this->chat->user_info;
        if ($userAction) {
            if ($userInfo->status < 9) {
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
            } elseif ($userInfo->status == 12) {
                switch ($userAction->screen) {
                    case 'changing_tall':
                        $statusStore = TelegramUserInfoService::change_tall($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_weight':
                        $statusStore = TelegramUserInfoService::change_weight($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_goal_weight':
                        $statusStore = TelegramUserInfoService::change_goal_weight($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_age':
                        $statusStore = TelegramUserInfoService::change_age($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                }
            } else {
                $keywordButton = TelegramButtonService::findMessageKeyword($text);
                // $this->chat->message($keywordButton ? $keywordButton :'dddd')->send();
                if ($keywordButton) {
                    if (method_exists(TelegramButtonService::class, $keywordButton))
                        TelegramButtonService::$keywordButton($this->chat);
                    else
                        $this->chat->message('topilmadi bu komanda')->send();
                } else {
                    $this->chat->message('topilmadi bu komanda')->send();
                }
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
            TelegramButtonService::home($this->chat);
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
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this->lang(json_encode($deletedMessages)));
    }
    public function entering_activity_type()
    {
        $activity_type_id = $this->data->get('activity_type_id');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->activity_type_id = $activity_type_id;
        $userInfo->status = 8;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this->lang('activity_type_selected'));
    }
    public function selecting_gender()
    {
        $gender = $this->data->get('gender');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->gender = $gender;
        $userInfo->status = 3;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this->lang('gender_selected'));
    }

    public function start_again_user_info()
    {
        $userInfo = $this->chat->user_info;
        $userInfo->status = 2;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        UserActionService::add($this->chat, 'selecting_gender');
        TelegramUserInfoService::check_user_info($this->chat, $userInfo);
        $this->reply($this->lang('started_again_user_info'));
    }
    public function confirm_user_info()
    {
        $userInfo = $this->chat->user_info;
        $userInfo->status = 11;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $this->reply($this->lang('user_info_confirmed'));
        TelegramButtonService::home($this->chat);
        $resultMenuImage = MenuImageGeneratorService::generateMenuImageForOneUser($userInfo);
        $menuPartImage = MenuImageGeneratorService::generateMenuPartsImageForOneUser($userInfo);
        TempMessage::create([
            'text_response' => json_encode($resultMenuImage)
        ]);
        TempMessage::create([
            'text_response' => json_encode($menuPartImage)
        ]);
        TelegramButtonService::full_menu($this->chat);
    }

    public function change_language()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        UserActionService::add($this->chat, 'changing_language');
        $text = self::lang('select_language');
        $ttt = $this->chat->message($text)
            ->keyboard(Keyboard::make()->buttons([
                Button::make('UZ')->action('changing_lang')->param('lang', 'uz'),
                Button::make('RU')->action('changing_lang')->param('lang', 'ru'),
            ]))->send();
        $this->reply($this->lang('language_changing'));
    }
    public function change_gender()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        UserActionService::add($this->chat, 'changing_gender');
        $text = self::lang('select_gender');
        $this->chat->message($text)
            ->keyboard(Keyboard::make()->buttons([
                Button::make('Ayol')->action('changing_gender')->param('gender', '0'),
                Button::make('Erkak')->action('changing_gender')->param('gender', '1'),
            ]))->send();
        $this->reply($this->lang('gender_changing'));
    }

    public function change_tall()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $userInfo = $this->chat->user_info;
        $userInfo->status = 12;
        $userInfo->update();
        $this->reply($this->lang('tall_changing'));
        $text = self::lang('enter_tall');
        UserActionService::add($this->chat, 'changing_tall');
        $this->chat->html($text)->send();

    }
    public function change_weight()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $userInfo = $this->chat->user_info;
        $userInfo->status = 12;
        $userInfo->update();
        $this->reply($this->lang('weight_changing'));
        $text = self::lang('enter_weight');
        UserActionService::add($this->chat, 'changing_weight');
        $this->chat->html($text)->send();
    }
    public function change_age()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $userInfo = $this->chat->user_info;
        $userInfo->status = 12;
        $userInfo->update();
        $this->reply($this->lang('age_changing'));
        $text = self::lang('enter_age');
        UserActionService::add($this->chat, 'changing_age');
        $this->chat->html($text)->send();
    }
    public function change_goal_weight()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $userInfo = $this->chat->user_info;
        $userInfo->status = 12;
        $userInfo->update();
        $this->reply($this->lang('goal_weight_changing'));
        $normalWeight = TelegramUserInfoService::calculate_average_goal_weight($userInfo);
        if ($normalWeight['status']) {
            $text = self::lang('enter_goal_weight') . PHP_EOL . self::lang('normal_weight_for_you') . ' : ' . $normalWeight['normal_weight']['from'] . ' - ' . $normalWeight['normal_weight']['to'] . ' (kg)';
        } else {
            $text = self::lang('enter_goal_weight');
        }
        UserActionService::add($this->chat, 'changing_goal_weight');
        $this->chat->html($text)->send();
    }

    public function changing_lang()
    {
        $userInfo = $this->chat->user_info;
        $lang = $this->data->get('lang');
        $userInfo->language = $lang;
        $userInfo->update();
        app()->setLocale($lang);
        $deletedMessages = [$this->messageId];
        $this->reply($this->lang('language_changed'));
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramButtonService::change_user_info($this->chat);
    }
    public function changing_gender()
    {
        $userInfo = $this->chat->user_info;
        $gender = $this->data->get('gender');
        $userInfo->gender = $gender;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        TelegramUserInfoService::calculate_daily_spend_calories($this->chat);
        $this->reply($this->lang('gender_changed'));
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramButtonService::change_user_info($this->chat);
    }
}
