<?php

use Carbon\Carbon;

if (!function_exists('message_lang')) {
    function message_lang($messageKeyWord, $locale = null)
    {
        if ($locale == null) {
            $locale = app()->getLocale() ? app()->getLocale() : 'uz';
        }
        $messages = config('message_langs');
        if (key_exists($messageKeyWord, $messages[$locale])) {
            return $messages[$locale][$messageKeyWord];
        } else {
            return $messageKeyWord;
        }
    }
}
