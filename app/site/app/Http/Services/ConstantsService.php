<?php

namespace App\Http\Services;

use Illuminate\Support\Arr;

class ConstantsService
{
    public static function get($key)
    {
        return Arr::get(self::CONSTANTS, $key);
    }

    const CONSTANTS = [
        'nico-emails' => [
            'naoki19940317@yahoo.co.jp',
        ],
        'nico-image-dir' => 'image/',
    ];
}
