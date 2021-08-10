<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\EncryptionHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class EncryptionHelperTest extends AbstractTestCase
{
    /**
     * @test
     *
     * @dataProvider provideDataForSha1Detection
     */
    public function detects_sha1_string(string $string, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, EncryptionHelper::isSha1($string));
    }

    public function provideDataForSha1Detection(): array
    {
        return [
            ['b232ae11d18d56b0fc6223ea5d291fe3149a8d15', true],
            ['b232ae11d18d56b0fc6223ea5d291fe3149a8d156', false],
            ['b232ae11d18d56b0fc6223ea5d291fe3149a8d1', false],
            ['$2y$10$/A8DW4tDjUxtcrXg/LgOHeMDNvWJH/QvJE35heWrYb5AA0mxLy.I6', false],
        ];
    }
}
