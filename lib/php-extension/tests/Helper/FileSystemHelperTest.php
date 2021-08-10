<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\FileSystemHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @internal
 *
 * @small
 */
final class FileSystemHelperTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_create_and_delete_dirs(): void
    {
        $tmpPath = __DIR__ . '/some/deep/file/path';

        self::assertFalse(is_dir($tmpPath) && is_file($tmpPath) && is_link($tmpPath));

        FileSystemHelper::createDirIfNotExists($tmpPath);

        self::assertTrue(is_dir($tmpPath));

        FileSystemHelper::deleteDir(__DIR__ . '/some');

        self::assertFalse(is_dir($tmpPath) && is_file($tmpPath) && is_link($tmpPath));
    }
}
