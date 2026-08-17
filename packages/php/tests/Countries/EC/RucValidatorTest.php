<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Tests\Countries\EC;

use Codaminds\Identifiers\Countries\EC\NationalIdValidator;
use Codaminds\Identifiers\Countries\EC\RucValidator;
use Codaminds\Identifiers\Identifier;
use Codaminds\Identifiers\Tests\Support\LoadsTestVectors;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RucValidatorTest extends TestCase
{
    use LoadsTestVectors;

    private RucValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new RucValidator(new NationalIdValidator);
    }

    /**
     * @return array<string, array{0: string, 1: bool, 2: ?string}>
     */
    public static function vectorDataProvider(): array
    {
        $data = self::loadVector('EC', 'tax-id');

        $dataset = [];
        foreach ($data['cases'] as $case) {
            $dataset[$case['description']] = [
                $case['input'],
                (bool) $case['expected'],
                $case['errorCode'] ?? null,
            ];
        }

        return $dataset;
    }

    #[Test]
    public function it_has_correct_metadata(): void
    {
        $this->assertSame('EC', $this->validator->countryCode());
        $this->assertSame('tax-id', $this->validator->identifierType());
    }

    #[Test]
    #[DataProvider('vectorDataProvider')]
    public function it_validates_ruc_against_test_vectors(string $input, bool $expected, ?string $errorCode): void
    {
        $result = $this->validator->validate($input);

        $this->assertSame($expected, $result->isValid);
        $this->assertSame('EC', $result->country);
        $this->assertSame('tax-id', $result->identifierType);

        if (! $expected) {
            $this->assertNotNull($result->errorCode);
            $this->assertNotNull($result->errorMessage);

            if ($errorCode !== null) {
                $this->assertSame($errorCode, $result->errorCode);
            }
        }
    }

    #[Test]
    public function it_integrates_seamlessly_with_identifier_facade(): void
    {
        $this->assertTrue(Identifier::isValid('EC', 'tax-id', '0926687856001'));
        $this->assertFalse(Identifier::isValid('EC', 'tax-id', '0926687856000'));
    }

    #[Test]
    public function it_accepts_ruc_with_common_separators(): void
    {
        $this->assertTrue($this->validator->validate('0926687856-001')->isValid);
        $this->assertTrue($this->validator->validate('0926687856 001')->isValid);
    }
}
