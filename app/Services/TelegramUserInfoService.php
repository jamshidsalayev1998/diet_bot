<?php

namespace App\Services;

use App\Models\V1\ActivityType;
use App\Models\V1\UserInfo;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Validator;

class TelegramUserInfoService
{
    public static function check_user_info($chat)
    {
        $userInfo = self::check_exists_user_info($chat);
        switch ($userInfo->status) {
            case 1:
                $text = 'Tilni tanlang';
                UserActionService::add($chat, 'entering_lang');
                Telegraph::message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('UZ')->action('entering_lang')->param('lang', 'uz'),
                        Button::make('RU')->action('entering_lang')->param('lang', 'ru'),
                    ]))->send();
                break;
            case 2:
                $text = 'Jinsingizni tanlang';
                UserActionService::add($chat, 'entering_gender');
                Telegraph::message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('Ayol')->action('entering_gender')->param('gender', '0'),
                        Button::make('Erkak')->action('entering_gender')->param('gender', '1'),
                    ]))->send();
                break;
            case 3:
                $text = 'Vazningizni kiriting';
                UserActionService::add($chat, 'entering_weight');
                $chat->html($text)->send();
                break;
            case 4:
                $text = 'Kozlangan vaznni kiriting';
                UserActionService::add($chat, 'entering_goal_weight');
                $chat->html($text)->send();
                break;
            case 5:
                $text = 'Bo`yingizni kiriting';
                UserActionService::add($chat, 'entering_tall');
                $chat->html($text)->send();
                break;
            case 6:
                $text = 'Yoshingizni kiriting';
                UserActionService::add($chat, 'entering_age');
                $chat->html($text)->send();
                break;
            case 7:
                $text = 'Aktivlik turini tanlang';
                UserActionService::add($chat, 'entering_activity_type');
                $activityTypes = ActivityType::all();
                $buttons = [];
                foreach ($activityTypes as $activityType) {
                    array_push($buttons, Button::make($activityType->title)->action('entering_activity_type')->param('activity_type_id', $activityType->id));
                }
                Telegraph::message($text)
                    ->keyboard(Keyboard::make()->buttons($buttons))->send();
                break;
            case 8:
                self::calculate_daily_spend_calories($chat);
                self::send_daily_spend_calories($chat);
                break;
            default:
                $text = 'nomalum status';
                $chat->html($text)->send();
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
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weight,

        ], [
            'weight' => ['required', 'integer']
        ]);
        if ($validator->failed()) {
            $status = 0;
            Telegraph::message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->weight = $weight;
            $userInfo->status = 4;
            $userInfo->update();
        }
        return $status;
    }
    public static function store_goal_weight($chat, $weight)
    {
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weight,

        ], [
            'weight' => ['required', 'integer']
        ]);
        if ($validator->failed()) {
            $status = 0;
            Telegraph::message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->goal_weight = $weight;
            $userInfo->status = 5;
            $userInfo->update();
        }
        return $status;
    }
    public static function store_tall($chat, $weight)
    {
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weight,

        ], [
            'weight' => ['required', 'integer']
        ]);
        if ($validator->failed()) {
            $status = 0;
            Telegraph::message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->tall = $weight;
            $userInfo->status = 6;
            $userInfo->update();
        }
        return $status;
    }
    public static function store_age($chat, $weight)
    {
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weight,

        ], [
            'weight' => ['required', 'integer']
        ]);
        if ($validator->failed()) {
            $status = 0;
            Telegraph::message('Vaznni kiritishda xatolik iltimos butun son kiriting!')->send();
        } else {
            $userInfo->age = $weight;
            $userInfo->status = 7;
            $userInfo->update();
        }
        return $status;
    }
}
