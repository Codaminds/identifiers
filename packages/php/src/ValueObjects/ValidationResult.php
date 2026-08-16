<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\ValueObjects;

final readonly class ValidationResult
{
    public function __construct(
        public bool $isValid,
        public string $country,
        public string $identifierType,
        public ?string $errorCode = null,
        public ?string $errorMessage = null
    ) {}

    public static function success(string $country, string $type): self
    {
        return new self(
            isValid: true,
            country: $country,
            identifierType: $type
        );
    }

    public static function failure(string $country, string $type, string $code, string $message): self
    {
        return new self(
            isValid: false,
            country: $country,
            identifierType: $type,
            errorCode: $code,
            errorMessage: $message
        );
    }

    public function isFailure(): bool
    {
        return ! $this->isValid;
    }
}
