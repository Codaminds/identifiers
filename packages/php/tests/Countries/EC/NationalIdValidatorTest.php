<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Tests\Countries\EC;

use Codaminds\Identifiers\Countries\EC\NationalIdValidator;
use Codaminds\Identifiers\Identifier;
use Codaminds\Identifiers\Tests\Support\LoadsTestVectors;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NationalIdValidatorTest extends TestCase
{
    use LoadsTestVectors;

    private NationalIdValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new NationalIdValidator();
    }

    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function vectorDataProvider(): array
    {
        $data = self::loadVector('EC', 'national-id');

        $dataset = [];
        foreach ($data['cases'] as $case) {
            $dataset[$case['description']] = [
                $case['input'],
                (bool) $case['expected'],
            ];
        }

        return $dataset;
    }

    #[Test]
    #[DataProvider('vectorDataProvider')]
    public function it_validates_national_id_against_test_vectors(string $input, bool $expected): void
    {
        $result = $this->validator->validate($input);

        $this->assertSame($expected, $result->isValid);
        $this->assertSame('EC', $result->country);
        $this->assertSame('national-id', $result->identifierType);

        if (!$expected) {
            $this->assertNotNull($result->errorCode);
            $this->assertNotNull($result->errorMessage);
        }
    }

    #[Test]
    public function it_integrates_seamlessly_with_identifier_facade(): void
    {
        $this->assertTrue(Identifier::isValid('EC', 'national-id', '0926687856'));
        $this->assertFalse(Identifier::isValid('EC', 'national-id', '0926687857'));
    }
}