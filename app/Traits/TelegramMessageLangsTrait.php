<?php

namespace App\Traits;

trait TelegramMessageLangsTrait
{
    // public static function lang($messageKeyWord)
    // {
    //     $messages = config('message_langs');
    //     $locale = app()->getLocale() ? app()->getLocale() : 'uz';
    //     if (key_exists($messageKeyWord, $messages[$locale])) {
    //         return $messages[$locale][$messageKeyWord];
    //     } else {
    //         return $messageKeyWord;
    //     }
    // }
    public static function lang($messageKeyWord, $variables = [])
    {
        $messages = config('message_langs');
        $locale = app()->getLocale() ? app()->getLocale() : 'uz';

        if (key_exists($messageKeyWord, $messages[$locale])) {
            $message = $messages[$locale][$messageKeyWord];

            // Replace placeholders with actual values
            foreach ($variables as $key => $value) {
                $message = str_replace(":$key", $value, $message);
            }

            return $message;
        } else {
            return $messageKeyWord;
        }
    }

    public static function buttonLang($messageKeyWord)
    {
        $messages = config('button_message_translaters');
        $locale = app()->getLocale() ? app()->getLocale() : 'uz';
        if (key_exists($messageKeyWord, $messages)) {
            return $messages[$messageKeyWord][$locale];
        } else {
            return $messageKeyWord;
        }
    }
}
