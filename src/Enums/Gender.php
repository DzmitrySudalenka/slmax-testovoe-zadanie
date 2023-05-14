<?php

namespace App\Enums;

enum Gender: int {
    case Female = 0;
    case Male = 1;

    public static function genderByNum(int $num): string {
        return match($num) {
            0 => 'жен.',
            1 => 'муж.',
        };
    }
}
