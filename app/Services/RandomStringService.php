<?php

namespace App\Services;

class RandomStringService {
    public static function randomNumberHelper($count)
    {
        $alphabet = '123456789';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $count; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public static function randomAlphaHelper($count)
    {
        $alphabet = 'qwertyuiopasdfghjklzxcvbnm';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $count; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
    public static function randomAlphaAndNumberHelper($count)
    {
        $alphabet = 'QWERTYUPASDFGHJKMNBVCXZ123456789';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $count; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
}
