<?php

namespace App\Services;

use App\Models\V1\ActivityType;
use App\Models\V1\UserInfo;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

class TelegramUserInfoService
{
    use TelegramMessageLangsTrait;
    public static function check_user_info($chat, $userInfo = null)
    {
        if (!$userInfo)
            $userInfo = self::check_exists_user_info($chat);
        // $userInfo = $chat->user_info;
        switch ($userInfo->status) {
            case 1:
                $text = self::lang('select_language');
                UserActionService::add($chat, 'entering_lang');
                $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('UZ')->action('entering_lang')->param('lang', 'uz'),
                        Button::make('RU')->action('entering_lang')->param('lang', 'ru'),
                    ]))->send();
                break;
            case 2:
                $text = self::lang('select_gender');
                UserActionService::add($chat, 'selecting_gender');
                $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('Ayol')->action('selecting_gender')->param('gender', '0'),
                        Button::make('Erkak')->action('selecting_gender')->param('gender', '1'),
                    ]))->send();
                break;
            case 3:
                $text = self::lang('enter_tall');
                UserActionService::add($chat, 'entering_tall');
                $chat->html($text)->send();
                break;
            case 4:
                $text = self::lang('enter_weight');
                UserActionService::add($chat, 'entering_weight');
                $chat->html($text)->send();
                break;

            case 5:
                $normalWeight = self::calculate_average_goal_weight($userInfo);
                if ($normalWeight['status']) {
                    $text = self::lang('enter_goal_weight') . PHP_EOL . self::lang('normal_weight_for_you') . ' : ' . $normalWeight['normal_weight'] . ' kg';
                    $chat->message($text)->replyKeyboard(ReplyKeyboard::make()->button($normalWeight['normal_weight'])->resize()->oneTime())->send();
                } else {
                    $text = self::lang('enter_goal_weight');
                    $chat->html($text)->send();
                }
                UserActionService::add($chat, 'entering_goal_weight');
                break;
            case 6:
                $text = self::lang('enter_age');
                UserActionService::add($chat, 'entering_age');
                $chat->html($text)->removeReplyKeyboard()->send();
                break;
            case 7:
                $text = self::lang('select_activity_type');
                UserActionService::add($chat, 'entering_activity_type');
                $activityTypes = ActivityType::all();
                $buttons = [];
                foreach ($activityTypes as $activityType) {
                    $title = json_decode($activityType->title, true);
                    array_push($buttons, Button::make($title[app()->getLocale()])->action('entering_activity_type')->param('activity_type_id', $activityType->id));
                }
                $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons($buttons))->send();
                break;
            case 8:
                self::calculate_daily_spend_calories($chat);
                // self::send_daily_spend_calories($chat);
                self::send_user_info_confirmation_message($chat,$userInfo);
                break;
            default:
                $text = self::lang('select_language');
                UserActionService::add($chat, 'entering_lang');
                $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('UZ')->action('entering_lang')->param('lang', 'uz'),
                        Button::make('RU')->action('entering_lang')->param('lang', 'ru'),
                    ]))->send();
        }
    }

    public static function send_daily_spend_calories($chat)
    {
        $userInfo = $chat->user_info;
        $text = 'Sizning kunlik kkal sarfingiz : ' . $userInfo->daily_spend_calories;
        $chat->html($text)->send();
    }

    public static function calculate_daily_spend_calories($chat)
    {
        $calories = null;
        $userInfo = $chat->user_info;
        if ($userInfo->gender) {
            $calories = 66 + 13.7 * $userInfo->weight + 5 * $userInfo->tall - 6.76 * $userInfo->age;
        } else {
            $calories = 655 + 9.6 * $userInfo->weight + 1.8 * $userInfo->tall - 4.7 * $userInfo->age;
        }
        $activityType = $userInfo->activity_type;
        $calories *= $activityType->coefficient;
        $userInfo->daily_spend_calories = round($calories);
        $userInfo->status = 10;
        $userInfo->update();
    }

    public static function check_exists_user_info($chat)
    {
        $userInfo = $chat->user_info;
        if (!$userInfo) {
            $userInfo = UserInfo::create([
                'chat_id' => $chat->chat_id,
            ]);
        }
        return $userInfo;
    }

    public static function store_weight($chat, $weight)
    {
        $weightString = (string) $weight;
        $weightInteger = intval($weightString);
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weightInteger,

        ], [
            'weight' => ['required', 'integer', 'between:20,200']
        ]);
        if ($validator->fails()) {
            $status = 0;
            $errors = $validator->errors()->all();
            $chat->message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $normalWeight = self::calculate_average_goal_weight($userInfo);
            if ($normalWeight['status']) {
                if ($normalWeight['normal_weight'] > $weight->toFloat()) {
                    $chat->message(self::lang('your_weight_is_small_than_normal'))->send();
                    $status = 0;
                } elseif ($normalWeight['normal_weight'] == $weight->toFloat()) {
                    $chat->message(self::lang('your_weight_is_equal_to_normal'))->send();
                    $status = 0;
                } else {
                    $userInfo->weight = $weight;
                    $userInfo->status = 5;
                    $userInfo->update();
                }
            }
        }
        return $status;
    }
    public static function store_goal_weight($chat, $weight)
    {
        $weightString = (string) $weight;
        $weightInteger = intval($weightString);
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weightInteger,

        ], [
            'weight' => ['required', 'integer', 'between:20,200']
        ]);
        if ($validator->failed()) {
            $status = 0;
            $chat->message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $normalWeight = self::calculate_average_goal_weight($userInfo);
            if ($normalWeight['status']) {
                if ($normalWeight['normal_weight'] > $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_goal_weight_is_small_than_normal'))->send();
                } elseif ($userInfo->weight < $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_weight_is_small_than_goal_weight'))->send();
                } elseif ($userInfo->weight == $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_weight_is_equal_to_goal_weight'))->send();
                } else {
                    $userInfo->goal_weight = $weight;
                    $userInfo->status = 6;
                    $userInfo->update();
                }
            }
        }
        return $status;
    }
    public static function store_tall($chat, $weight)
    {
        $weightString = (string) $weight;
        $weightInteger = intval($weightString);
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weightInteger,

        ], [
            'weight' => ['required', 'integer', 'between:40,300']
        ]);
        if ($validator->failed()) {
            $status = 0;
            $chat->message('Bo`yni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->tall = $weight;
            $userInfo->status = 4;
            $userInfo->update();
        }
        return $status;
    }
    public static function store_age($chat, $weight)
    {
        $weightString = (string) $weight;
        $weightInteger = intval($weightString);
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weightInteger,

        ], [
            'weight' => ['required', 'integer', 'between:10,80']
        ]);
        if ($validator->failed()) {
            $status = 0;
            $chat->message('Yoshni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->age = $weight;
            $userInfo->status = 7;
            $userInfo->update();
        }
        return $status;
    }

    public static function calculate_average_goal_weight($userInfo)
    {
        $status = 1;
        $weight = 0;
        if ($userInfo->status >= 4) {
            $weight = 22 * $userInfo->tall * $userInfo->tall / 10000;
            if ($userInfo->gender == 0) {
                $weight -= 5;
            }
        } else {
            $status = 0;
        }
        return [
            'status' => $status,
            'normal_weight' => round($weight)
        ];
    }

    public static function send_user_info_confirmation_message($chat, $userInfo)
    {
        $titleActivity = json_decode($userInfo->activity_type->title,true);
        $text = '🇺🇿' . self::lang('language') . ' : ' . self::lang($userInfo->language) . PHP_EOL;
        $text .= '👬' . self::lang('gender') . ' : ' . $userInfo->gender ? self::lang('man'):self::lang('woman') . PHP_EOL;
        $text .= '↕️' . self::lang('tall') . ' : ' . $userInfo->tall .' sm'. PHP_EOL;
        $text .= '🏗' . self::lang('weight') . ' : ' . $userInfo->weight .' kg'. PHP_EOL;
        $text .= '🥇' . self::lang('goal_weight') . ' : ' . $userInfo->goal_weight .' kg'. PHP_EOL;
        $text .= '🎂' . self::lang('age') . ' : ' . $userInfo->age . PHP_EOL;
        $text .= '⛹🏻' . self::lang('activity_type') . ' : ' . $titleActivity[app()->getLocale()] . PHP_EOL;
        $chat->message($text)->send();
    }
}
