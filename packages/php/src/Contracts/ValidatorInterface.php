<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Contracts;

use Codaminds\Identifiers\ValueObjects\ValidationResult;

interface ValidatorInterface
{
    public function countryCode(): string;

    public function identifierType(): string;

    public function validate(string $value): ValidationResult;
}
