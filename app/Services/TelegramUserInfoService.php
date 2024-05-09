<?php

namespace App\Services;

use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class TelegramUserInfoService
{
    public static function check_user_info($chat)
    {
        $userInfo = $chat->user_info;
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
                $text = 'Vazningizni kiriting';
                UserActionService::add($chat, 'entering_weight');
                $chat->html($text)->send();
                break;
            case 3:
                $text = 'Kozlangan vaznni kiriting';
                UserActionService::add($chat, 'entering_goal_weight');
                $chat->html($text)->send();
                break;
            case 4:
                $text = 'Bo`yingizni kiriting';
                UserActionService::add($chat, 'entering_tall');
                $chat->html($text)->send();
                break;
            case 5:
                $text = 'Yoshingizni kiriting';
                UserActionService::add($chat, 'entering_age');
                $chat->html($text)->send();
                break;
            case 6:
                $text = 'Aktivlik turini tanlang';
                UserActionService::add($chat, 'entering_activity_type');
                $chat->html($text)->send();
                break;
            case 7:
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
        $userInfo->status = 9;
        $userInfo->update();
    }


}
