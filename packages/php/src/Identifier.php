<?php

declare(strict_types=1);

namespace Codaminds\Identifiers;

use Codaminds\Identifiers\Contracts\ValidatorInterface;
use Codaminds\Identifiers\Countries\EC\NationalIdValidator;
use Codaminds\Identifiers\Countries\EC\RucValidator;
use Codaminds\Identifiers\ValueObjects\ValidationResult;
use InvalidArgumentException;

final class Identifier
{
    /** @var array<string, ValidatorInterface> */
    private static array $validators = [];

    public static function validate(string $country, string $type, string $value): ValidationResult
    {
        self::bootDefaultValidators();
        $key = self::resolveKey($country, $type);

        if (! isset(self::$validators[$key])) {
            throw new InvalidArgumentException("Unsupported identifier validator: [{$key}]");
        }

        return self::$validators[$key]->validate($value);
    }

    public static function isValid(string $country, string $type, string $value): bool
    {
        return self::validate($country, $type, $value)->isValid;
    }

    public static function register(ValidatorInterface $validator): void
    {
        $key = self::resolveKey($validator->countryCode(), $validator->identifierType());
        self::$validators[$key] = $validator;
    }

    /**
     * Resets the registry state (useful for test isolation).
     */
    public static function clear(): void
    {
        self::$validators = [];
    }

    private static function bootDefaultValidators(): void
    {
        if (empty(self::$validators)) {
            $nationalIdValidator = new NationalIdValidator;

            self::register($nationalIdValidator);
            self::register(new RucValidator($nationalIdValidator));
        }
    }

    private static function resolveKey(string $country, string $type): string
    {
        return strtoupper(trim($country)).':'.strtolower(trim($type));
    }
}
