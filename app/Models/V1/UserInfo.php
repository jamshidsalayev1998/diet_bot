<?php

namespace App\Models\V1;

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
}
