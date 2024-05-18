<?php

namespace App\Services;

use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;

class TelegramButtonService
{
    use TelegramMessageLangsTrait;
    public static function home($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make(self::buttonLang('menu')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('settings')),
                ReplyButton::make(self::buttonLang('support')),
            ])->resize();
        $chat->replyKeyboard($keyboard)->send();
    }

    public static function menu($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make(self::buttonLang('breakfasts')),
                ReplyButton::make(self::buttonLang('lunches')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('dinners')),
                ReplyButton::make(self::buttonLang('home')),
            ])->resize();
        $chat->replyKeyboard($keyboard)->send();
    }

    public static function findMessageKeyword($text)
    {
        $buttonTexts = config('button_message_translaters');
        $keyword = null;
        foreach ($buttonTexts as $key => $buttonText) {
            foreach ($buttonText as $buttonTextLang) {
                if ($buttonTextLang == $text) {
                    $keyword = $key;
                    break;
                }
            }
            if ($keyword) break;
        }
        return $keyword;
    }
}
