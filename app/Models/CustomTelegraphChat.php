<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\V1\UserInfo;
use App\Models\V1\CalcAiConversation;
class CustomTelegraphChat extends TelegraphChat
{
    use HasFactory;

    public function user_info(){
        return $this->hasOne(UserInfo::class , 'chat_id' , 'chat_id');
    }

    public function calc_ai_conversation(){
        return $this->hasOne(CalcAiConversation::class , 'chat_id' , 'chat_id')->where('status' , 1);
    }

}
