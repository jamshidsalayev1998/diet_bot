<?php

namespace App\Models\V1;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalcAiConversation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function telegraph_chat(){
        return $this->belongsTo(TelegraphChat::class , 'chat_id' , 'chat_id');
    }
}
