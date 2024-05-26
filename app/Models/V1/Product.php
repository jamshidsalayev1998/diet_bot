<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function measure_type(){
        return $this->belongsTo(MeasureType::class , 'measure_type_id' , 'id');
    }

    public function measure_cup(){
        return $this->belongsTo(MeasureCup::class , 'measure_cup_id' , 'id');
    }


}
