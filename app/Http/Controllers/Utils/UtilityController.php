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

    public static function countAge($birthdate) {
        //date in mm/dd/yyyy format; or it can be in other formats as well
        $birthDate = date('m/d/Y', strtotime($birthdate));
        //explode the date to get month, day and year
        $birthDate = explode("/", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        return $age;
    }
}
