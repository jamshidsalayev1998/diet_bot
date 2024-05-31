<?php
namespace App\Services;

use Carbon\Carbon;

class WeekDayService {
    public static function get_week_day_on_word($date , $format = 'd-M'){
        $locale = app()->getLocale();
        if($locale == 'uz') $locale = 'uz_Latn';
        Carbon::setLocale($locale);
        return Carbon::createFromFormat('Y-m-d' , $date)->translatedFormat('d-M');
    }
}
