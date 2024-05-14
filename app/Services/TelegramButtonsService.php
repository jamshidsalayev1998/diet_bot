<?php

namespace App\Services;

use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

class TelegramButtonsService
{
    use TelegramMessageLangsTrait;
    public static function detect_button_command($text)
    {
    }

    public static function home($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('🥘' . self::lang('menu')),
            ])->resize()
            ->row([
                ReplyButton::make('⚙️' . self::lang('settings')),
                ReplyButton::make('👨‍💻' . self::lang('support')),
            ])->resize();
        $chat->message('your_user_info_stored')->replyKeyboard($keyboard)->send();
    }

    public static function menu($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('⚙️' . self::lang('breakfasts')),
                ReplyButton::make('👨‍💻' . self::lang('lunches')),
            ])->resize()
            ->row([
                ReplyButton::make('🥘' . self::lang('ozuqalar ro`yhati')),
            ])->resize()
            ->row([
                ReplyButton::make('🥘' . self::lang('back')),
            ])->resize();

        $chat->message('your_user_info_stored')->replyKeyboard($keyboard)->send();
    }
}
