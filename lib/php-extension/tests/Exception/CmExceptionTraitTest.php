<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Exception;

use Acme\PhpExtension\Exception\CmExceptionInterface;
use Acme\PhpExtension\Exception\CmExceptionTrait;
use Acme\PhpExtension\Test\AbstractTestCase;
use Exception;

/**
 * @small
 * @group unit
 *
 * @internal
 */
final class CmExceptionTraitTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function construct_without_arguments(): void
    {
        $exception = new DummyException();

        self::assertSame('DummyException', $exception->getMessage());
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
        $previous = new Exception();

        $exception = new class($message, $code, $previous) extends Exception implements CmExceptionInterface {
            use CmExceptionTrait;
        };

        self::assertSame($message, $exception->getMessage());
        self::assertSame($code, $exception->getCode());
        self::assertSame($previous, $exception->getPrevious());
    }
}
