<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPart extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function menu_part_products(){
        return $this->hasMany(MenuPartProduct::class , 'menu_part_id' , 'id');
    }

    public function menu_type(){
        return $this->belongsTo(MenuType::class , 'menu_type_id' , 'id');
    }
}
