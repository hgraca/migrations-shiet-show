<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Entity;

use DateTimeImmutable;

trait UpdatedAtTimestamp
{
    /** @var DateTimeImmutable|null */
    private $updatedAt;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
