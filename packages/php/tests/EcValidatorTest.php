<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Tests;

use Codaminds\Identifiers\EcValidator;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

final class EcValidatorTest extends TestCase
{
    public static function vectorDataProvider(): array
    {
        $jsonPath = __DIR__ . '/../test-vectors/EC/national-id.json';

        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("No se encontró el archivo de vectores en: {$jsonPath}");
        }

        $content = (string) file_get_contents($jsonPath);
        $data = json_decode($content, true);

        $dataset = [];
        foreach ($data['cases'] as $case) {
            $dataset[$case['description']] = [$case['input'], $case['expected']];
        }

        return $dataset;
    }

    #[DataProvider('vectorDataProvider')]
    public function testValidateNationalId(string $input, bool $expected): void
    {
        $this->assertSame($expected, EcValidator::validateNationalId($input));
    }
}