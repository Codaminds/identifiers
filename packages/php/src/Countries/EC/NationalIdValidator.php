<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Countries\EC;

use Codaminds\Identifiers\Contracts\ValidatorInterface;
use Codaminds\Identifiers\ValueObjects\ValidationResult;

final class NationalIdValidator implements ValidatorInterface
{
    private const COUNTRY = 'EC';

    private const TYPE = 'national-id';

    private const COEFFICIENTS = [2, 1, 2, 1, 2, 1, 2, 1, 2];

    public function countryCode(): string
    {
        return self::COUNTRY;
    }

    public function identifierType(): string
    {
        return self::TYPE;
    }

    public function validate(string $value): ValidationResult
    {
        $sanitized = preg_replace('/[-\s]/', '', trim($value));

        if (! preg_match('/^\d{10}$/', $sanitized)) {
            return ValidationResult::failure(
                self::COUNTRY,
                self::TYPE,
                'INVALID_FORMAT',
                'Identifier must be exactly 10 numeric digits'
            );
        }

        $province = (int) substr($sanitized, 0, 2);
        if (! (($province >= 1 && $province <= 24) || $province === 30)) {
            return ValidationResult::failure(
                self::COUNTRY,
                self::TYPE,
                'INVALID_PROVINCE_CODE',
                'Province code must be between 01-24 or 30'
            );
        }

        $thirdDigit = (int) $sanitized[2];
        if ($thirdDigit >= 6) {
            return ValidationResult::failure(
                self::COUNTRY,
                self::TYPE,
                'INVALID_THIRD_DIGIT',
                'Third digit must be less than 6 for natural persons'
            );
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $product = ((int) $sanitized[$i]) * self::COEFFICIENTS[$i];
            $sum += ($product >= 10) ? ($product - 9) : $product;
        }

        $verifier = (10 - ($sum % 10)) % 10;
        if ($verifier !== (int) $sanitized[9]) {
            return ValidationResult::failure(
                self::COUNTRY,
                self::TYPE,
                'INVALID_CHECKSUM',
                'Verification digit does not match Luhn mod 10 algorithm'
            );
        }

        return ValidationResult::success(self::COUNTRY, self::TYPE);
    }
}
