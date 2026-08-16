<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Countries\EC;

use Codaminds\Identifiers\Contracts\ValidatorInterface;
use Codaminds\Identifiers\ValueObjects\ValidationResult;

final class RucValidator implements ValidatorInterface
{
    private const COUNTRY_CODE = 'EC';

    private const IDENTIFIER_TYPE = 'tax-id';

    private const MIN_PROVINCE = 1;

    private const MAX_PROVINCE = 24;

    private const JURISDICTION_EXTERIOR = 30;

    private const THIRD_DIGIT_PUBLIC = 6;

    private const THIRD_DIGIT_PRIVATE = 9;

    /** @var list<int> */
    private const COEFFICIENTS_PRIVATE = [4, 3, 2, 7, 6, 5, 4, 3, 2];

    /** @var list<int> */
    private const COEFFICIENTS_PUBLIC = [3, 2, 7, 6, 5, 4, 3, 2];

    public function __construct(
        private readonly ?ValidatorInterface $nationalIdValidator = null
    ) {}

    public function countryCode(): string
    {
        return self::COUNTRY_CODE;
    }

    public function identifierType(): string
    {
        return self::IDENTIFIER_TYPE;
    }

    public function validate(string $value): ValidationResult
    {
        $sanitized = trim($value);

        if (! ctype_digit($sanitized)) {
            return $this->failure('INVALID_FORMAT', 'Value must contain only numeric digits');
        }

        if (strlen($sanitized) !== 13) {
            return $this->failure('INVALID_LENGTH', 'Value must be exactly 13 digits');
        }

        $province = (int) substr($sanitized, 0, 2);
        if (($province < self::MIN_PROVINCE || $province > self::MAX_PROVINCE) && $province !== self::JURISDICTION_EXTERIOR) {
            return $this->failure('INVALID_PROVINCE_CODE', 'Province code must be between 01 and 24, or 30');
        }

        $thirdDigit = (int) $sanitized[2];

        // 1. Persona Natural: Tercer dígito de 0 a 5
        if ($thirdDigit < self::THIRD_DIGIT_PUBLIC) {
            return $this->validateNaturalPerson($sanitized);
        }

        // 2. Entidad Pública: Tercer dígito igual a 6
        if ($thirdDigit === self::THIRD_DIGIT_PUBLIC) {
            return $this->validatePublicEntity($sanitized);
        }

        // 3. Sociedad Privada / Extranjero sin cédula: Tercer dígito igual a 9
        if ($thirdDigit === self::THIRD_DIGIT_PRIVATE) {
            return $this->validatePrivateCompany($sanitized);
        }

        // Dígitos 7 y 8 son reservados / inválidos
        return $this->failure('INVALID_THIRD_DIGIT', "Third digit {$thirdDigit} is invalid for Ecuadorian RUC");
    }

    private function validateNaturalPerson(string $value): ValidationResult
    {
        $establishment = substr($value, 10, 3);
        if ((int) $establishment === 0) {
            return $this->failure('INVALID_ESTABLISHMENT', 'Establishment code must be greater than zero');
        }

        $nationalId = substr($value, 0, 10);
        $validator = $this->nationalIdValidator ?? new NationalIdValidator;
        $result = $validator->validate($nationalId);

        if ($result->isFailure()) {
            return $this->failure(
                $result->errorCode ?? 'INVALID_CHECKSUM',
                $result->errorMessage ?? 'Verification digit does not match algorithm'
            );
        }

        return $this->success();
    }

    private function validatePrivateCompany(string $value): ValidationResult
    {
        $establishment = substr($value, 10, 3);
        if ((int) $establishment === 0) {
            return $this->failure('INVALID_ESTABLISHMENT', 'Establishment code must be greater than zero');
        }

        $verifier = (int) $value[9];
        $expectedVerifier = $this->computeMod11Verifier($value, self::COEFFICIENTS_PRIVATE);

        if ($verifier !== $expectedVerifier) {
            return $this->failure('INVALID_CHECKSUM', 'Verification digit does not match algorithm');
        }

        return $this->success();
    }

    private function validatePublicEntity(string $value): ValidationResult
    {
        $establishment = substr($value, 9, 4);
        if ((int) $establishment === 0) {
            return $this->failure('INVALID_ESTABLISHMENT', 'Establishment code must be greater than zero');
        }

        $verifier = (int) $value[8];
        $expectedVerifier = $this->computeMod11Verifier($value, self::COEFFICIENTS_PUBLIC);

        if ($verifier !== $expectedVerifier) {
            return $this->failure('INVALID_CHECKSUM', 'Verification digit does not match algorithm');
        }

        return $this->success();
    }

    /**
     * @param  list<int>  $coefficients
     */
    private function computeMod11Verifier(string $value, array $coefficients): int
    {
        $sum = 0;
        $total = count($coefficients);

        for ($i = 0; $i < $total; $i++) {
            $sum += ((int) $value[$i]) * $coefficients[$i];
        }

        $remainder = $sum % 11;

        return ($remainder === 0) ? 0 : (11 - $remainder);
    }

    private function success(): ValidationResult
    {
        return ValidationResult::success(self::COUNTRY_CODE, self::IDENTIFIER_TYPE);
    }

    private function failure(string $errorCode, string $errorMessage): ValidationResult
    {
        return ValidationResult::failure(self::COUNTRY_CODE, self::IDENTIFIER_TYPE, $errorCode, $errorMessage);
    }
}
