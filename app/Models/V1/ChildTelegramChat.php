<?php

namespace App\Models\V1;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildTelegramChat extends TelegraphChat
{
    use HasFactory;

    public function user_info(){
        return $this->hasOne(UserInfo::class , 'chat_id' , 'chat_id');
    }
}
