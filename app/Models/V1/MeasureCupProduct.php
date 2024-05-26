<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasureCupProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function measure_cup(){
        return $this->belongsTo(MeasureCup::class , 'measure_cup_id' , 'id');
    }
}
