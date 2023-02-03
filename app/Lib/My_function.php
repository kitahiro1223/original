<?php

namespace App\Lib;

class My_function
{   
    public static function xss($i) {
        $i = isset($i) ? $i : '';
        $i = htmlspecialchars($i, ENT_QUOTES, 'UTF-8');
        return $i;
    }

}