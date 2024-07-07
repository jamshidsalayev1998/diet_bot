<?php

namespace App\Services;

use App\Models\TempMessage;
use App\Models\V1\ActivityType;
use App\Models\V1\MenuSize;
use App\Models\V1\UserInfo;
use App\Models\V1\UserWeightHistory;
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
                $text = self::lang('entering_fio');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_fio');
                $ttt = $chat->message($text)->send();
                break;
            case 0:
                $text = self::lang('select_language');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_lang');
                $ttt = $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make(self::lang('uz'))->action('entering_lang')->param('lang', 'uz'),
                        Button::make(self::lang('ru'))->action('entering_lang')->param('lang', 'ru'),
                    ]))->send();


                break;
            case 2:
                $text = self::lang('select_gender');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'selecting_gender');
                $chat->message($text)
                    ->keyboard(Keyboard::make()->buttons([
                        Button::make('Ayol')->action('selecting_gender')->param('gender', '0'),
                        Button::make('Erkak')->action('selecting_gender')->param('gender', '1'),
                    ]))->send();
                break;
            case 3:
                $text = self::lang('enter_tall');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_tall');
                $chat->html($text)->send();
                break;
            case 4:
                $text = self::lang('enter_weight');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_weight');
                $chat->html($text)->send();
                break;

            case 5:
                $normalWeight = self::calculate_average_goal_weight($userInfo);
                if ($normalWeight['status']) {
                    $text = self::lang('enter_goal_weight') . PHP_EOL . self::lang('normal_weight_for_you') . ' : ' . $normalWeight['normal_weight']['from'] . ' - ' . $normalWeight['normal_weight']['to'] . ' (kg)';
                    $chat->message($text)->send();
                } else {
                    $text = self::lang('enter_goal_weight');
                    $chat->html($text)->send();
                }
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_goal_weight');
                break;
            case 6:
                $text = self::lang('enter_age');
                UserActionService::remove($chat);
                UserActionService::add($chat, 'entering_age');
                $chat->html($text)->removeReplyKeyboard()->send();
                break;
            case 7:
                $text = self::lang('select_activity_type');
                UserActionService::remove($chat);
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
                self::send_user_info_confirmation_message($chat, $userInfo);
                break;
            default:
                $text = self::lang('select_language');
                UserActionService::remove($chat);
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
        $spendCalories = round($calories);
        if ($userInfo->weight > $userInfo->goal_weight) {
            $needCalories = round($calories) - 500;
        } else {
            $needCalories = round($calories) + 500;
        }
        $userInfo->daily_spend_calories = $spendCalories;
        $userInfo->daily_need_calories = $needCalories;
        $firstMenu = MenuSize::where('calories', '<=', $needCalories)->orderBy('calories', 'DESC')->first();
        if (!$firstMenu) {
            $firstMenu = MenuSize::where('calories', '>=', $needCalories)->orderBy('calories', 'DESC')->first();
        }
        $userInfo->menu_size_id = $firstMenu->id;
        $userInfo->status = 10;
        $userInfo->update();
    }

    public static function re_calculate_daily_spend_calories($chat)
    {
        $calories = null;
        $userInfo = $chat->user_info;
        $oldMenuSizeId = $userInfo->menu_size_id;
        if ($userInfo->gender) {
            $calories = 66 + 13.7 * $userInfo->weight + 5 * $userInfo->tall - 6.76 * $userInfo->age;
        } else {
            $calories = 655 + 9.6 * $userInfo->weight + 1.8 * $userInfo->tall - 4.7 * $userInfo->age;
        }
        $activityType = $userInfo->activity_type;
        $calories *= $activityType->coefficient;
        $spendCalories = round($calories);
        if ($userInfo->weight > $userInfo->goal_weight) {
            $needCalories = round($calories) - 500;
        } else {
            $needCalories = round($calories) + 500;
        }
        $userInfo->daily_spend_calories = $spendCalories;
        $userInfo->daily_need_calories = $needCalories;
        $firstMenu = MenuSize::where('calories', '<=', $needCalories)->orderBy('calories', 'DESC')->first();
        if (!$firstMenu) {
            $firstMenu = MenuSize::where('calories', '>=', $needCalories)->orderBy('calories', 'DESC')->first();
        }
        $userInfo->menu_size_id = $firstMenu->id;
        $userInfo->update();
        if ($oldMenuSizeId != $firstMenu->id) {
            $userInfo->menu_image = null;
            $userInfo->menu_part_images = null;
            $userInfo->update();
            $resultGenerateMenu = MenuImageGeneratorService::generateMenuImageForOneUser($userInfo);
            if (!$resultGenerateMenu['status']) {
                $chat->message('menu generate error ' . json_encode($resultGenerateMenu))->send();
            }
            $resultGenerateMenuPart = MenuImageGeneratorService::generateMenuPartsImageForOneUser($userInfo);
            if (!$resultGenerateMenuPart['status']) {
                $chat->message('menu parts generate error ' . json_encode($resultGenerateMenuPart))->send();
            }
        }
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
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            // $normalWeight = self::calculate_average_goal_weight($userInfo);
            // if ($normalWeight['status']) {
            // if ($normalWeight['normal_weight']['from'] > $weight->toFloat()) {
            //     $chat->message(self::lang('your_weight_is_small_than_normal'))->send();
            //     $status = 0;
            // } elseif ($normalWeight['normal_weight']['from'] <= $weight->toFloat() && $normalWeight['normal_weight']['to'] >= $weight->toFloat()) {
            //     $chat->message(self::lang('your_weight_is_equal_to_normal'))->send();
            //     $status = 0;
            // } else {
            $userInfo->weight = $weightString;
            $userInfo->status = 5;
            $userInfo->update();
            UserActionService::remove($chat);
            // }
            // }
        }
        return $status;
    }
    public static function change_weight($chat, $weight)
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
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $normalWeight = self::calculate_average_goal_weight($userInfo);
            if ($normalWeight['status']) {
                if ($normalWeight['normal_weight']['from'] > $weight->toFloat()) {
                    $chat->message(self::lang('your_weight_is_small_than_normal'))->send();
                    $status = 0;
                } elseif ($normalWeight['normal_weight']['from'] <= $weight->toFloat() && $normalWeight['normal_weight']['to'] >= $weight->toFloat()) {
                    $chat->message(self::lang('your_weight_is_equal_to_normal'))->send();
                    $status = 0;
                } else {
                    $userInfo->weight = $weightString;
                    $userInfo->update();
                    UserActionService::remove($chat);
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
        if ($validator->fails()) {
            $status = 0;
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $normalWeight = self::calculate_average_goal_weight($userInfo);
            if ($normalWeight['status']) {
                if ($normalWeight['normal_weight']['from'] > $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_goal_weight_is_small_than_normal'))->send();
                } elseif ($normalWeight['normal_weight']['to'] < $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_goal_weight_is_big_than_normal'))->send();
                } elseif ($userInfo->weight == $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_weight_is_equal_to_goal_weight'))->send();
                } else {
                    $userInfo->goal_weight = $weight;
                    $userInfo->status = 6;
                    $userInfo->update();
                    UserActionService::remove($chat);
                }
            }
        }
        return $status;
    }
    public static function change_goal_weight($chat, $weight)
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
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $normalWeight = self::calculate_average_goal_weight($userInfo);
            if ($normalWeight['status']) {
                if ($normalWeight['normal_weight']['from'] > $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_goal_weight_is_small_than_normal'))->send();
                } elseif ($userInfo->weight < $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_weight_is_small_than_goal_weight'))->send();
                } elseif ($userInfo->weight == $weight->toFloat()) {
                    $status = 0;
                    $chat->message(self::lang('your_weight_is_equal_to_goal_weight'))->send();
                } else {
                    $userInfo->goal_weight = $weightString;
                    $userInfo->update();
                    UserActionService::remove($chat);
                }
            }
        }
        return $status;
    }
    public static function store_tall($chat, $tall)
    {
        $tallString = (string) $tall;
        $status = 1;
        $userInfo = $chat->user_info;
        // $chat->message($tallString)->send();
        $validator = Validator::make([
            'tall' => $tallString,
        ], [
            'tall' => ['required', 'integer', 'min:50', 'max:300']
        ]);
        if ($validator->fails()) {
            $status = 0;
            $chat->message(self::lang('enter_the_correct_format_of_tall'))->send();
        } else {
            $userInfo->tall = $tallString;
            $userInfo->status = 4;
            $userInfo->update();
            UserActionService::remove($chat);
        }
        return $status;
    }

    public static function change_tall($chat, $tall)
    {
        $weightString = (string) $tall;
        $weightInteger = intval($weightString);
        $status = 1;
        $userInfo = $chat->user_info;
        $validator = Validator::make([
            'weight' => $weightInteger,

        ], [
            'weight' => ['required', 'integer', 'between:40,300']
        ]);
        if ($validator->fails()) {
            $status = 0;
            $chat->message(self::lang('enter_the_correct_format_of_tall'))->send();
        } else {
            $userInfo->tall = $weightString;
            $userInfo->update();
            UserActionService::remove($chat);
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
        if ($validator->fails()) {
            $status = 0;
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $userInfo->age = $weightInteger;
            $userInfo->status = 7;
            $userInfo->update();
            UserActionService::remove($chat);
        }
        return $status;
    }
    public static function change_age($chat, $weight)
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
        if ($validator->fails()) {
            $status = 0;
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $userInfo->age = $weightInteger;
            $userInfo->update();
            UserActionService::remove($chat);
        }
        return $status;
    }

    public static function calculate_average_goal_weight($userInfo)
    {
        $status = 1;
        $weightFrom = 0;
        $weightTo = 0;
        if ($userInfo->status >= 4) {
            $weightFrom = 18.5 * $userInfo->tall * $userInfo->tall / 10000;
            $weightTo = 25 * $userInfo->tall * $userInfo->tall / 10000;
        } else {
            $status = 0;
        }
        return [
            'status' => $status,
            'normal_weight' => [
                'from' => round($weightFrom),
                'to' => round($weightTo)
            ]
        ];
    }

    public static function send_user_info_confirmation_message($chat, $userInfo)
    {
        $titleActivity = json_decode($userInfo->activity_type->title, true);
        if ($userInfo->gender) {
            $genderTitle = self::lang('man');
        } else {
            $genderTitle = self::lang('woman');
        }
        $text = '🇺🇿 ' . self::lang('language') . ' : ' . self::lang($userInfo->language) . PHP_EOL . PHP_EOL;
        $text .= '👬 ' . self::lang('gender') . ' : ' . $genderTitle . PHP_EOL . PHP_EOL;
        // $text .= '👬' . self::lang('gender') . ' : ' . $userInfo->gender ? self::lang('man') : self::lang('woman') . PHP_EOL . PHP_EOL;
        $text .= '↕️ ' . self::lang('tall') . ' : ' . $userInfo->tall . ' sm' . PHP_EOL . PHP_EOL;
        $text .= '⚖️ ' . self::lang('weight') . ' : ' . $userInfo->weight . ' kg' . PHP_EOL . PHP_EOL;
        $text .= '🥇 ' . self::lang('goal_weight') . ' : ' . $userInfo->goal_weight . ' kg' . PHP_EOL . PHP_EOL;
        $text .= '🎂 ' . self::lang('age') . ' : ' . $userInfo->age . PHP_EOL . PHP_EOL;
        $text .= '⛹🏻 ' . self::lang('activity_type') . ' : ' . $titleActivity[app()->getLocale()] . PHP_EOL . PHP_EOL;
        $text .= '' . self::lang('daily_need') . ' : ' . $userInfo->daily_need_calories . ' kkal' . PHP_EOL . PHP_EOL;
        $chat->message($text)->keyboard(Keyboard::make()->buttons([
            Button::make(self::lang('confirm'))->action('confirm_user_info')->param('lang', 'uz'),
            Button::make(self::lang('start_again'))->action('start_again_user_info')->param('lang', 'ru'),
        ]))->send();
    }

    public static function this_action_for_premium($chat)
    {
        $text = self::lang('for_this_action_need_premium');
        $chat->message($text)->send();
    }

    public static function track_message($text, $date = null)
    {
        if ($date == null) $date = date('Y-m-d');
        $keyboard = Keyboard::make()
            ->row([
                Button::make(self::lang('full_follow_a_diet'))->action('daily_track_request')->param('answer', $date . '|2'),
            ])
            ->row([
                Button::make(self::lang('partially_follow_a_diet'))->action('daily_track_request')->param('answer', $date . '|1'),
            ])
            ->row([
                Button::make(self::lang('did_not_follow_a_diet'))->action('daily_track_request')->param('answer', $date . '|0'),
            ]);
        return [
            'text' => $text,
            'keyboard' => $keyboard
        ];
    }

    public static function enter_weight_history($chat, $weight)
    {
        $userInfo = $chat->user_info;
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
            $chat->message(self::lang('enter_the_correct_format'))->send();
        } else {
            $oldWeight = $userInfo->weight;
            if (!UserWeightHistory::where('weight', $oldWeight)->where('chat_id', $chat->chat_id)->exists()) {
                UserWeightHistory::create([
                    'chat_id' => $chat->chat_id,
                    'weight' => $oldWeight
                ]);
            }
            if (!UserWeightHistory::where('weight', $weight)->where('chat_id', $chat->chat_id)->exists()) {
                UserWeightHistory::create([
                    'chat_id' => $chat->chat_id,
                    'weight' =>  $weightString
                ]);
            }
            $userInfo->weight =  $weightString;
            $userInfo->update();
            UserActionService::remove($chat);
        }
        return $status;
    }

    public static function send_group_link($chat, $userInfo)
    {
        $linkGroup = config('projectDefaultValues.group_link');
        if ($userInfo->gender) {
            $text = self::lang('group_link_text_for_man');
        } else {
            $text = self::lang('group_link_text_for_woman');
        }
        $chat->message($text)->keyboard(Keyboard::make()->buttons([
            Button::make(self::lang('enter_the_group'))->url($linkGroup[$userInfo->gender]),
        ]))->send();
    }

    public static function store_fio($chat,$fio){
        $fioString = (string) $fio;
        $userInfo = $chat->user_info;
        $userInfo->fio = $fioString;
        $userInfo->status = 2;
        $userInfo->update();
        return 1;
    }
}
