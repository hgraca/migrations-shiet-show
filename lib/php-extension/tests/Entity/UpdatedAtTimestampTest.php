<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Entity;

use Acme\PhpExtension\Entity\UpdatedAtTimestamp;
use Acme\PhpExtension\Test\AbstractTestCase;
use DateTimeImmutable;

/**
 * @internal
 *
 * @small
 */
final class UpdatedAtTimestampTest extends AbstractTestCase
{
    /** @var DatetimeImmutable|null */
    private $update;

    protected function setUp(): void
    {
        $this->update = new class() {
            use UpdatedAtTimestamp;
        };
    }

    /**
     * @test
     */
    public function gets_update(): void
    {
        $this->update->touch();
        self::assertInstanceOf(DateTimeImmutable::class, $this->update->getUpdatedAt());
    }

    /**
     * @test
     */
    public function can_update(): void
    {
        $beforeUpdate = $this->update->getUpdatedAt();
        $this->update->touch();
        $afterUpdate = $this->update->getUpdatedAt();

        self::assertNull($beforeUpdate);
        self::assertInstanceOf(DateTimeImmutable::class, $afterUpdate);
    }
}
