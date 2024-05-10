<?php

namespace App\Traits;

trait TelegramMessageLangsTrait
{
    public static function lang($messageKeyWord)
    {
        $messages = config('message_langs');
        $locale = app()->getLocale() ? app()->getLocale() : 'uz';
        if (key_exists($messageKeyWord, $messages[$locale])) {
            return $messages[$locale][$messageKeyWord];
        } else {
            return $messageKeyWord;
        }
    }
}
