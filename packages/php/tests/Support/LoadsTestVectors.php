<?php

declare(strict_types=1);

namespace Codaminds\Identifiers\Tests\Support;

use JsonException;
use RuntimeException;

trait LoadsTestVectors
{
    /**
     * @return array{country: string, identifier: string, cases: array<int, array{input: string, expected: bool, description: string}>}
     * @throws JsonException
     */
    protected static function loadVector(string $country, string $identifier): array
    {
        $baseDir = $_ENV['TEST_VECTORS_DIR'] ?? getenv('TEST_VECTORS_DIR') ?: null;

        if ($baseDir === null || !is_dir($baseDir)) {
            $fallback = realpath(__DIR__ . '/../../../../test-vectors');
            $baseDir = $fallback !== false ? $fallback : '/app/test-vectors';
        }

        $filePath = rtrim($baseDir, '/') . '/' . strtoupper($country) . '/' . $identifier . '.json';

        if (!file_exists($filePath)) {
            throw new RuntimeException("Test vector not found at: {$filePath}");
        }

        $content = (string) file_get_contents($filePath);

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}