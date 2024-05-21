<?php

namespace App\Services;

use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Facades\Storage;

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
        $chat->message(self::lang('welcome'))->replyKeyboard($keyboard)->send();
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
        $chat->message(self::lang('welcome_menus_page'))->replyKeyboard($keyboard)->send();
    }

    public static function breakfasts($chat)
    {
        $userInfo = $chat->user_info;
        $breakfastPath = '';
        if($userInfo){
            if($userInfo->menu_part_images){
                $menuPartImages = json_decode($userInfo->menu_part_images,true);
                if(key_exists(1,$menuPartImages)){
                    $breakfastPath = $menuPartImages[1];
                }
                else{
                    $chat->message(self::lang('breakfasts_no'))->send();
                }
            }
            else{
                $chat->message(self::lang('user_info_not_full'))->send();
            }
        }
        else{
            $chat->message(self::lang('user_ino_not_found'))->send();
        }
        if($breakfastPath){
            $chat->photo(Storage::path($breakfastPath))->send();
        }else{
            $chat->message(self::lang('something_error'))->send();

        }
    }

    public static function findMessageKeyword($text)
    {
        $buttonTexts = config('button_message_translaters');
        $keyword = null;
        foreach ($buttonTexts as $key => $buttonText) {
            foreach ($buttonText as $buttonTextLang) {
                if ($buttonTextLang == $text) {
                    $keyword = $key;
                }
                break;
            }
            if ($keyword) break;
        }
        return $keyword;
    }
}
