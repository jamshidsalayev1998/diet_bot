<?php

namespace App\Services;

use App\Models\V1\ActivityType;
use App\Models\V1\UserInfo;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;
use App\Traits\TelegramMessageLangsTrait;

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
                if($normalWeight['status']){
                    $text = self::lang('enter_goal_weight').PHP_EOL.self::lang('normal_weight_for_you').' : '.$normalWeight['normal_weight'].' kg';
                }
                else{
                    $text = self::lang('enter_goal_weight');
                }
                UserActionService::add($chat, 'entering_goal_weight');
                $chat->html($text)->send();
                break;
            case 6:
                $text = self::lang('enter_age');
                UserActionService::add($chat, 'entering_age');
                $chat->html($text)->send();
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
                self::send_daily_spend_calories($chat);
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
        $userInfo = $chat->user_info;
        $userInfo->daily_spend_calories = 2345;
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
            $chat->message('Vaznni kiritishda xatolik iltimos butun son kiriting! .' . json_encode($errors))->send();
        } else {
            $userInfo->weight = $weight;
            $userInfo->status = 5;
            $userInfo->update();
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
            $userInfo->goal_weight = $weight;
            $userInfo->status = 6;
            $userInfo->update();
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
        if ($userInfo->gender != null && $userInfo->tall) {
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
}
