<?php

namespace App\Models\V1;

use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function activity_type(){
        return $this->belongsTo(ActivityType::class , 'activity_type_id' , 'id');
    }

    public function menu_size(){
        return $this->belongsTo(MenuSize::class , 'menu_size_id' , 'id');
    }

    public function chat(){
        return $this->belongsTo(TelegraphChat::class , 'chat_id' , 'chat_id');
    }
}
