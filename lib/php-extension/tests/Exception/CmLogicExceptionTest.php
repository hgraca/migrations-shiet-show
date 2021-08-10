<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Exception;

use Acme\PhpExtension\Exception\CmLogicException;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class CmLogicExceptionTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function construct_without_arguments(): void
    {
        $exception = new CmLogicException();

        self::assertSame('CmLogicException', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertNull($exception->getPrevious());
    }

    /**
     * @test
     */
    public function construct_with_arguments(): void
    {
        $message = 'some_message';
        $code = 666;
        $previous = new CmLogicException();

        $exception = new CmLogicException($message, $code, $previous);

        self::assertSame($message, $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
