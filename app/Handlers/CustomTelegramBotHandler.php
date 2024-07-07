<?php

namespace App\Handlers;

use App\Models\TempMessage;
use App\Models\Track\DailyTrackReport;
use App\Models\V1\ActivityType;
use App\Models\V1\CalcAiConversation;
use App\Models\V1\ChildTelegramChat;
use App\Models\V1\UserInfo;
use App\Services\CalcAi\CalcAiService;
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
use Illuminate\Support\Facades\Storage;
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
                    case 'entering_fio':
                        $statusStore = TelegramUserInfoService::store_fio($this->chat, $text);
                        if ($statusStore) {
                            $this->chat->message($this->lang('saved'))->send();
                            TelegramUserInfoService::check_user_info($this->chat);
                        }
                        break;
                    case 'entering_weight':
                        $statusStore = TelegramUserInfoService::store_weight($this->chat, $text);
                        if ($statusStore) {
                            $this->chat->message($this->lang('saved'))->send();
                            TelegramUserInfoService::check_user_info($this->chat);
                        }
                        break;
                    case 'entering_goal_weight':
                        $statusStore = TelegramUserInfoService::store_goal_weight($this->chat, $text);
                        if ($statusStore) {
                            $this->chat->message($this->lang('saved'))->send();
                            TelegramUserInfoService::check_user_info($this->chat);
                        }
                        break;
                    case 'entering_tall':
                        $statusStore = TelegramUserInfoService::store_tall($this->chat, $text);
                        if ($statusStore) {
                            $this->chat->message($this->lang('saved'))->send();
                            TelegramUserInfoService::check_user_info($this->chat);
                        }
                        break;
                    case 'entering_age':
                        $statusStore = TelegramUserInfoService::store_age($this->chat, $text);
                        if ($statusStore) {
                            $this->chat->message($this->lang('saved'))->send();
                            TelegramUserInfoService::check_user_info($this->chat);
                        }
                        break;
                }
            } elseif ($userInfo->status == 11) {
                switch ($userAction->screen) {
                    case 'entering_changing_of_weight':
                        $statusStore = TelegramUserInfoService::enter_weight_history($this->chat, $text);
                        if ($statusStore) {
                            $text = self::lang('weight_changing_history_stored');
                            TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
                            $this->chat->message($text)->send();
                            TelegramButtonService::my_user_info($this->chat);
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
                            TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_weight':
                        $statusStore = TelegramUserInfoService::change_weight($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_goal_weight':
                        $statusStore = TelegramUserInfoService::change_goal_weight($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                    case 'changing_age':
                        $statusStore = TelegramUserInfoService::change_age($this->chat, $text);
                        if ($statusStore) {
                            $userInfo->status = 11;
                            $userInfo->update();
                            TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
                            TelegramButtonService::change_user_info($this->chat);
                        }
                        break;
                }
            }
        } else {
            TempMessage::create([
                'text_response' => json_encode($this->message)
            ]);
            TempMessage::create([
                'text_response' => json_encode($this->message->photos())
            ]);
            $keywordButton = TelegramButtonService::findMessageKeyword($text);
            if ($keywordButton) {
                if (method_exists(TelegramButtonService::class, $keywordButton)) {
                    TelegramButtonService::$keywordButton($this->chat);
                } else {
                    $this->chat->message('topilmadi bu komanda lal')->send();
                }
            } else {
                $calc_ai_conversation = $this->chat->calc_ai_conversation;
                if ($calc_ai_conversation) {
                    CalcAiService::connect_with_ai($this->chat, $text, $this->message->photos(), $calc_ai_conversation, $this->bot->token, $userInfo);
                } else {
                    $this->chat->message('topilmadi bu komanda ')->send();
                }
            }
        }
    }
    protected function handleUnknownCommand(Stringable $text): void
    {
        $this->chat->html("I can't understand your command: $text")->send();
    }
    public function start()
    {
        TempMessage::create([
            'text_response' => json_encode($this->request->all())
        ]);
        $dataRequest = $this->request->all();
        $userInfo = $this->chat->user_info;
        $this->chat->message($this->lang('welcome_for_new_user'))->removeReplyKeyboard()->send();
        if (!$userInfo) {
            $userInfo = UserInfo::create([
                'chat_id' => $this->chat->chat_id,
                'fio' => key_exists('last_name', $dataRequest['message']['from']) ? $dataRequest['message']['from']['last_name'] . ' ' . $dataRequest['message']['from']['first_name'] : $this->chat->name
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
        $userInfo->status = 1;
        $userInfo->update();
        app()->setLocale($lang);
        UserActionService::remove($this->chat);
        $deletedMessages = [$this->messageId];
        // $this->chat->message($this->lang('saved'))->send();
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramUserInfoService::check_user_info($this->chat);
        $this->reply($this->lang('language_saved'));
    }
    public function entering_activity_type()
    {
        $activity_type_id = $this->data->get('activity_type_id');
        $userInfo = TelegramUserInfoService::check_exists_user_info($this->chat);
        $userInfo->activity_type_id = $activity_type_id;
        $userInfo->status = 8;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        // $this->chat->message($this->lang('saved'))->send();
        UserActionService::remove($this->chat);
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
        // $this->chat->message($this->lang('saved'))->send();
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
        TelegramButtonService::home($this->chat);
        MenuImageGeneratorService::generateMenuImageForOneUser($userInfo);
        MenuImageGeneratorService::generateMenuPartsImageForOneUser($userInfo);
        TelegramButtonService::full_menu($this->chat);
        TelegramUserInfoService::send_group_link($this->chat, $userInfo);
    }

    public function change_language()
    {
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        UserActionService::remove($this->chat);
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
        // TelegramButtonService::profile($this->chat);
        // TelegramButtonService::my_user_info($this->chat);
        UserActionService::remove($this->chat);
        TelegramButtonService::home($this->chat);
    }
    public function changing_gender()
    {
        $userInfo = $this->chat->user_info;
        $gender = $this->data->get('gender');
        $userInfo->gender = $gender;
        $userInfo->update();
        TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
        $deletedMessages = [$this->messageId];
        $this->reply($this->lang('gender_changed'));
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramButtonService::change_user_info($this->chat);
        UserActionService::remove($this->chat);
    }
    public function change_activity_type()
    {

        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $text = self::lang('select_activity_type');
        $activityTypes = ActivityType::all();
        $buttons = [];
        foreach ($activityTypes as $activityType) {
            $title = json_decode($activityType->title, true);
            array_push($buttons, Button::make($title[app()->getLocale()])->action('changing_activity_type')->param('activity_type_id', $activityType->id));
        }
        $this->chat->message($text)
            ->keyboard(Keyboard::make()->buttons($buttons))->send();
        $this->reply($this->lang('activity_type_changing'));
    }

    public function changing_activity_type()
    {
        $userInfo = $this->chat->user_info;
        $activity_type_id = $this->data->get('activity_type_id');
        $userInfo->activity_type_id = $activity_type_id;
        $userInfo->update();
        $deletedMessages = [$this->messageId];
        TelegramUserInfoService::re_calculate_daily_spend_calories($this->chat);
        $this->reply($this->lang('activity_type_changed'));
        $this->chat->deleteMessages($deletedMessages)->send();
        TelegramButtonService::change_user_info($this->chat);
        UserActionService::remove($this->chat);
    }

    public function ai_commenting()
    {
        $calc_ai_conversation_id = $this->data->get('calc_ai_conversation_id');
        $conversation = CalcAiConversation::find($calc_ai_conversation_id);
        if ($conversation) {
            if ($conversation->status == 1) {
                $commentKeyboard = Keyboard::make()
                    ->row([
                        Button::make(self::lang('ai_result_is_correct'))->action('ai_deleting')->param('calc_ai_conversation_id', $calc_ai_conversation_id),
                    ]);
                CalcAiConversation::where('id', $calc_ai_conversation_id)->update(['commenting' => 1]);
                $this->chat->message(self::lang('tell_me_about_this_product'))->keyboard($commentKeyboard)->send();
            } else {
                $this->chat->message(self::lang('sorry_this_calc_ai_conversation_deleted'))->send();
            }
        }
        $this->reply($this->lang('comment'));
    }

    public function ai_deleting()
    {
        $userInfo = $this->chat->user_info;
        $calc_ai_conversation_id = $this->data->get('calc_ai_conversation_id');
        $conversation = CalcAiConversation::find($calc_ai_conversation_id);
        if ($conversation) {
            CalcAiService::delete_product_ai($conversation->product_id, $this->bot->token);
            CalcAiService::edit_deleted_result_of_ai(json_decode($conversation->response, true), $this->chat, $userInfo, $this->messageId);
        }
        $this->reply($this->lang('ai_product_deleted'));
        CalcAiConversation::create([
            'chat_id' => $this->chat->chat_id,
            'status' => 1
        ]);
    }
    // public function ai_result_is_correct()
    // {
    //     $userInfo = $this->chat->user_info;
    //     $calc_ai_conversation_id = $this->data->get('calc_ai_conversation_id');
    //     $conversation = CalcAiConversation::find($calc_ai_conversation_id);
    //     if ($conversation) {
    //         CalcAiService::delete_product_ai($conversation->product_id, $this->bot->token);
    //         CalcAiService::edit_deleted_result_of_ai(json_decode($conversation->response,true),$this->chat,$userInfo,$this->messageId);
    //     }
    //     $this->reply($this->lang('ai_product_deleted'));
    //     CalcAiConversation::create([
    //         'chat_id' => $this->chat->chat_id,
    //         'status' => 1
    //     ]);
    //     // $this->chat->message(self::lang('tell_me_about_this_product'))->send();
    // }

    public function daily_track_request()
    {
        $userInfo = $this->chat->user_info;
        $data = $this->data->get('answer');
        $dataParsed = explode('|', $data);
        // $this->chat->message($dataParsed[0])->send();
        $report = DailyTrackReport::where('date_report', $dataParsed[0])->where('chat_id', $this->chat->chat_id)->first();
        if (!$report) {
            $report = DailyTrackReport::create([
                'chat_id' => $this->chat->chat_id,
                'date_report' => $dataParsed[0],
                'answer' => 0
            ]);
        } else {
            $oldAnswer = $report->answer;
            $userInfo->track_scores -= $oldAnswer;
        }
        $userInfo->track_scores += $dataParsed[1];
        $report->answer = $dataParsed[1];
        $userInfo->update();
        $report->update();
        $text = '';
        if ($dataParsed[1] == 0) {
            $text = 'next_time_follow';
        } elseif ($dataParsed[1] == 1) {
            $text = 'next_time_be_more_active';
        } else {
            $text = 'next_time_also_be_active';
        }
        $this->chat->message(self::lang($text))->send();
        $deletedMessages = [$this->messageId];
        $this->chat->deleteMessages($deletedMessages)->send();
        $this->reply($this->lang('track_stored'));
    }
}
