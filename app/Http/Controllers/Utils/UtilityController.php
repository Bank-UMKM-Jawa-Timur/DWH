<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public static function clearCurrencyFormat($num) {
        $num = strtolower($num);
        $chars = ['rp', '.', ' '];
        $num = str_replace($chars, '', $num);
        if (str_contains($num, ',')) {
            // return 's';
            $num = str_replace(',', '.', $num);
        }

        return floatval($num);
    }
}
