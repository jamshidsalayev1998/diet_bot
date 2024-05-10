<?php

trait TelegramMessageLangsTrait{
    public function lang($messageKeyWord){
        $messages = config('message_langs');
        $locale = app()->getLocale();
        if(key_exists($messageKeyWord , $messages[$locale])){
            return $messages[$locale][$messageKeyWord];
        }
        else{
            return $messageKeyWord;
        }
    }
}
