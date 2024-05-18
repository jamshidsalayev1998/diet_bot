<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSize extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function menu_parts(){
        return $this->hasMany(MenuPart::class , 'menu_size_id' , 'id');
    }

}
