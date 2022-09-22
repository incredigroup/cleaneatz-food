<?php

namespace App\Models;

class RoyaltyMeta
{
    private static $meta = [
        'fees' => [
            0 => 'Royalties',
            1 => 'Ad Fund',
            2 => 'Advisory',
        ],
        'tiers' => [
            0 => 'Corporate',
            1 => 'Franchise',
            2 => 'Fanchise+',
        ],
        'fee_tier_amounts' => [
            0 => [
                0 => 1,
                1 => 1,
                2 => 1,
            ],
            1 => [
                0 => 7,
                1 => 1,
                2 => 1,
            ],
            2 => [
                0 => 8,
                1 => 2,
                2 => 1,
            ],
        ],
    ];

    public static function get()
    {
        return self::$meta;
    }
}
