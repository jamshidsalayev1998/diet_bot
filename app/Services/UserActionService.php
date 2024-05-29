<?php
namespace App\Services;

use App\Models\V1\UserAction;

class UserActionService {
    public static function add($chat ,string $screen){
        UserAction::create([
            'chat_id' => $chat->chat_id,
            'screen' => $screen
        ]);
    }
    public static function remove($chat){
        UserAction::query()->where('chat_id' , $chat->chat_id)->delete();
    }
}
