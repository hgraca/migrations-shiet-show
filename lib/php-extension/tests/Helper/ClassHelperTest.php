<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\ClassHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class ClassHelperTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function extract_canonical_class_name(): void
    {
        self::assertSame('ClassHelperTest', ClassHelper::extractCanonicalClassName(__CLASS__));
    }

    /**
     * @test
     */
    public function extract_canonical_method_name(): void
    {
        self::assertSame(
            'extract_canonical_method_name',
            ClassHelper::extractCanonicalMethodName(__METHOD__)
        );
    }
}
