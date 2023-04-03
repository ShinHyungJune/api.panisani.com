<?php
/**
 * Created by PhpStorm.
 * User: master
 * Date: 2021-01-31
 * Time: 오후 4:04
 */

namespace App\Enums;


final class State
{
    const AGREE = "승인"; // 승인
    const DENY = "반려"; // 반려

    public static function getValues()
    {
        return [self::AGREE, self::DENY];
    }
}
