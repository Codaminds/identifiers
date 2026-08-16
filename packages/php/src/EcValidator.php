<?php

declare(strict_types=1);

namespace Codaminds\Identifiers;

final class EcValidator
{
    public static function validateNationalId(string $value): bool
    {
        $clean = preg_replace('/[-\s]/', '', trim($value));

        if (!preg_match('/^\d{10}$/', $clean)) {
            return false;
        }

        $province = (int) substr($clean, 0, 2);
        if (!(($province >= 1 && $province <= 24) || $province === 30)) {
            return false;
        }

        $thirdDigit = (int) $clean[2];
        if ($thirdDigit >= 6) {
            return false;
        }

        $coefficients = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $product = ((int) $clean[$i]) * $coefficients[$i];
            if ($product >= 10) {
                $product -= 9;
            }
            $sum += $product;
        }

        $checkDigit = (10 - ($sum % 10)) % 10;

        return $checkDigit === ((int) $clean[9]);
    }
}