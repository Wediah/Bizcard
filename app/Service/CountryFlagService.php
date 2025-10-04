<?php

namespace App\Services;

class CountryFlagService
{
    public static function getFlag(?string $countryCode): string
    {
        if (!$countryCode || strlen($countryCode) !== 2) {
            return '🌐';
        }

        $code = strtoupper($countryCode);
        $firstLetter = 127397 + ord($code[0]);
        $secondLetter = 127397 + ord($code[1]);

        return mb_chr($firstLetter) . mb_chr($secondLetter);
    }
}
