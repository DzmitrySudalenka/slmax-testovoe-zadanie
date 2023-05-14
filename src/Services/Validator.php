<?php

namespace App\Services;

class Validator
{
    public static function isString($val)
    {
        return preg_match("/[A-Za-zА-ЯЁа-яё]+$/u", $val);
    }

    public static function isDate($val)
    {
        return preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", $val);
    }

    public static function isGender($val)
    {
        return preg_match("/^[0-1]{1}$/", $val);
    }

    public static function supportedSigns($val)
    {
        $supportedSigns = ['>', '<', '!='];
        return in_array($val, $supportedSigns);
    }
}
