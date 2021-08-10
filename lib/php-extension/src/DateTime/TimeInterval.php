<?php

declare(strict_types=1);

namespace Acme\PhpExtension\DateTime;

use DateTimeImmutable;

final class TimeInterval
{
    /** @var int */
    private $miliseconds;

    private function __construct(int $miliseconds)
    {
        $this->miliseconds = $miliseconds;
    }

    public static function fromSeconds(int $seconds): self
    {
        return new self($seconds * 1000);
    }

    public static function fromMinutes(int $minutes): self
    {
        return new self($minutes * 60 * 1000);
    }

    public static function fromHours(int $hours): self
    {
        return new self($hours * 60 * 60 * 1000);
    }

    public static function fromDays(int $days): self
    {
        return new self($days * 24 * 60 * 60 * 1000);
    }

    public static function until(DateTimeImmutable $dateTime): self
    {
        return new self(($dateTime->getTimestamp() - time()) * 1000);
    }

    public function getMiliseconds(): int
    {
        return $this->miliseconds;
    }

    /**
     * @return float|int
     */
    public function getSeconds()
    {
        return $this->miliseconds / 1000;
    }

    /**
     * @return float|int
     */
    public function getMinutes()
    {
        return $this->miliseconds / 60 / 1000;
    }

    /**
     * @return float|int
     */
    public function getHours()
    {
        return $this->miliseconds / 60 / 60 / 1000;
    }

    /**
     * @return float|int
     */
    public function getDays()
    {
        return $this->miliseconds / 24 / 60 / 60 / 1000;
    }
}
