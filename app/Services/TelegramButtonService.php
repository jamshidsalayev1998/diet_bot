<?php

namespace App\Services;

use App\Models\Track\DailyTrackReport;
use App\Traits\TelegramMessageLangsTrait;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
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
                ReplyButton::make(self::buttonLang('calc_dieto')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('send_today_track_report')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('menu')),
                ReplyButton::make(self::buttonLang('profile')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('my_results')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('support')),
            ])->resize();
        $chat->message(self::lang('welcome'))->replyKeyboard($keyboard)->send();
    }

    public static function my_results($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make(self::buttonLang('my_own_results')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('liga_results')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('home')),
            ])->resize();
        $chat->message(self::lang('welcome'))->replyKeyboard($keyboard)->send();
    }

    public static function menu($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make(self::buttonLang('full_menu')),
                ReplyButton::make(self::buttonLang('snacks')),
            ])->resize()
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
        if ($userInfo) {
            if ($userInfo->menu_part_images) {
                $menuPartImages = json_decode($userInfo->menu_part_images, true);
                if (key_exists(1, $menuPartImages)) {
                    $breakfastPath = $menuPartImages[1];
                } else {
                    $chat->message(self::lang('breakfasts_no'))->send();
                }
            } else {
                $chat->message(self::lang('user_info_not_full'))->send();
            }
        } else {
            $chat->message(self::lang('user_ino_not_found'))->send();
        }
        if ($breakfastPath) {
            // $chat->message('https://bot.dieto.uz/storage'.$breakfastPath)->send();
            $chat->photo('https://bot.dieto.uz/storage' . $breakfastPath)->send();
        } else {
            $chat->message(self::lang('something_error'))->send();
        }
    }
    public static function lunches($chat)
    {
        $userInfo = $chat->user_info;
        $breakfastPath = '';
        if ($userInfo) {
            if ($userInfo->menu_part_images) {
                $menuPartImages = json_decode($userInfo->menu_part_images, true);
                if (key_exists(2, $menuPartImages)) {
                    $breakfastPath = $menuPartImages[2];
                } else {
                    $chat->message(self::lang('breakfasts_no'))->send();
                }
            } else {
                $chat->message(self::lang('user_info_not_full'))->send();
            }
        } else {
            $chat->message(self::lang('user_ino_not_found'))->send();
        }
        if ($breakfastPath) {
            // $chat->message('https://bot.dieto.uz/storage'.$breakfastPath)->send();
            $chat->photo('https://bot.dieto.uz/storage' . $breakfastPath)->send();
        } else {
            $chat->message(self::lang('something_error'))->send();
        }
    }
    public static function dinners($chat)
    {
        $userInfo = $chat->user_info;
        $breakfastPath = '';
        if ($userInfo) {
            if ($userInfo->menu_part_images) {
                $menuPartImages = json_decode($userInfo->menu_part_images, true);
                if (key_exists(3, $menuPartImages)) {
                    $breakfastPath = $menuPartImages[3];
                } else {
                    $chat->message(self::lang('breakfasts_no'))->send();
                }
            } else {
                $chat->message(self::lang('user_info_not_full'))->send();
            }
        } else {
            $chat->message(self::lang('user_ino_not_found'))->send();
        }
        if ($breakfastPath) {
            // $chat->message('https://bot.dieto.uz/storage'.$breakfastPath)->send();
            $chat->photo('https://bot.dieto.uz/storage' . $breakfastPath)->send();
        } else {
            $chat->message(self::lang('something_error'))->send();
        }
    }
    public static function full_menu($chat)
    {
        $userInfo = $chat->user_info;
        $breakfastPath = '';
        if ($userInfo) {
            if ($userInfo->menu_image) {
                $breakfastPath = $userInfo->menu_image;
            } else {
                $chat->message(self::lang('user_info_not_full'))->send();
            }
        } else {
            $chat->message(self::lang('user_ino_not_found'))->send();
        }
        if ($breakfastPath) {
            $chat->message('https://bot.dieto.uz/storage'.$breakfastPath)->send();
            $chat->document('https://bot.dieto.uz/storage'.$breakfastPath)->send();
            $chat->photo('https://bot.dieto.uz/storage'.$breakfastPath)->send();
        } else {
            $chat->message(self::lang('something_error'))->send();
        }
    }

    public static function snacks($chat)
    {

        $chat->message(self::lang('soon'))->send();
    }

    public static function profile($chat)
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                // ReplyButton::make(self::buttonLang('my_user_info')),
                ReplyButton::make(self::buttonLang('enter_changing_of_weight')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('change_user_info')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('enter_bot_language')),
            ])->resize()
            ->row([
                ReplyButton::make(self::buttonLang('home')),
            ])->resize();
        $chat->message(self::lang('welcome_settings_page'))->replyKeyboard($keyboard)->send();
    }

    public static function my_user_info($chat)
    {
        $userInfo = $chat->user_info;
        $text = self::make_user_info_text($userInfo);
        $chat->message($text)->send();
    }

    public static function change_user_info($chat)
    {
        $userInfo = $chat->user_info;
        $text = self::make_user_info_text($userInfo);
        $text = 'Qaysi ma`lumotni o`zgartirmoqchisiz ?' . PHP_EOL . $text;
        $keyboard = Keyboard::make()
            ->row([
                Button::make(self::lang('gender'))->action('change_gender')->param('settings_button', 'change_gender'),
                Button::make(self::lang('tall'))->action('change_tall')->param('settings_button', 'change_tall'),
            ])
            ->row([
                Button::make(self::lang('weight'))->action('change_weight')->param('settings_button', 'change_weight'),
                Button::make(self::lang('goal_weight'))->action('change_goal_weight')->param('settings_button', 'change_goal_weight'),
            ])
            ->row([
                Button::make(self::lang('age'))->action('change_age')->param('settings_button', 'change_age'),
                Button::make(self::lang('activity_type'))->action('change_activity_type')->param('settings_button', 'change_activity_type'),
            ]);
        $chat->message($text)->keyboard($keyboard)->send();
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

    public static function make_user_info_text($userInfo)
    {
        $titleActivity = json_decode($userInfo->activity_type->title, true);
        if ($userInfo->gender) {
            $genderTitle = self::lang('man');
        } else {
            $genderTitle = self::lang('woman');
        }
        $text = '🇺🇿 ' . self::lang('language') . ' : ' . self::lang($userInfo->language) . PHP_EOL . PHP_EOL;
        $text .= '👬 ' . self::lang('gender') . ' : ' . $genderTitle . PHP_EOL . PHP_EOL;
        $text .= '↕️ ' . self::lang('tall') . ' : ' . $userInfo->tall . ' sm' . PHP_EOL . PHP_EOL;
        $text .= '⚖️ ' . self::lang('weight') . ' : ' . $userInfo->weight . ' kg' . PHP_EOL . PHP_EOL;
        $text .= '🥇 ' . self::lang('goal_weight') . ' : ' . $userInfo->goal_weight . ' kg' . PHP_EOL . PHP_EOL;
        $text .= '🎂 ' . self::lang('age') . ' : ' . $userInfo->age . PHP_EOL . PHP_EOL;
        $text .= '' . self::lang('daily_need') . ' : ' . $userInfo->daily_need_calories . PHP_EOL . PHP_EOL;
        return $text;
    }

    public static function support($chat)
    {
        $userInfo = $chat->user_info;
        if (!$userInfo->is_premium) {
            TelegramUserInfoService::this_action_for_premium($chat);
        } else {
            $text = self::lang('welcome_support_page');
            $chat->message($text)->send();
        }
    }

    public static function send_today_track_report($chat)
    {
        $time = date('HH:ii');
        if ($time >= '19:00') {
            $dailyTrackReport = DailyTrackReport::query()->where('date_report', date('Y-m-d'))->where('chat_id', $chat->chat_id)->orderBy('id', 'DESC');
            $text = date('Y-m-d') . ' ' . self::lang('for_this_day_how_did_you_follow') . PHP_EOL . self::lang('you_must_not_eat_until_tomorrow');
            $dataTrack = TelegramUserInfoService::track_message($text);
            $chat->message($dataTrack['text'])->keyboard($dataTrack['keyboard'])->send();
        } else {
            $text = self::lang('track_report_after_19_every_day');
            $chat->message($text)->send();
        }
    }

    public static function enter_changing_of_weight($chat)
    {
        UserActionService::remove($chat);
        UserActionService::add($chat, 'entering_changing_of_weight');
        $text = self::lang('enter_weight_of_right_now');
        $chat->message($text)->send();
    }

    public static function enter_bot_language($chat)
    {
        UserActionService::remove($chat);
        UserActionService::add($chat, 'changing_language');
        $text = self::lang('select_language');
        $chat->message($text)
            ->keyboard(Keyboard::make()->buttons([
                Button::make('UZ')->action('changing_lang')->param('lang', 'uz'),
                Button::make('RU')->action('changing_lang')->param('lang', 'ru'),
            ]))->send();
    }
}
