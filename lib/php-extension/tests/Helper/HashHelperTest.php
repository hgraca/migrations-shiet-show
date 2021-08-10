<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\HashHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @group unit
 * @small
 *
 * @internal
 */
final class HashHelperTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_hash_string(): void
    {
        self::assertEquals(
            '54786e6fd53792433f55c9cb74ae40a32d3ce33ea4cddd577b5133922d95ec80',
            HashHelper::hashString('test string')
        );
    }

    /**
     * @test
     *
     * @dataProvider HashedStringProvider
     */
    public function detects_sha256_string(bool $expectedResult, string $hashedString): void
    {
        self::assertEquals($expectedResult, HashHelper::isSha256($hashedString));
    }

    public function HashedStringProvider(): array
    {
        return [
            [true, '54786e6fd53792433f55c9cb74ae40a32d3ce33ea4cddd577b5133922d95ec80'],
            [false, 'b232ae11d18d56b0fc6223ea5d291fe3149a8d15'],
            [false, 'non-hashed string'],
        ];
    }
}
