<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Ulid;

use Acme\PhpExtension\Identity\AbstractId;
use function is_string;

abstract class AbstractUlid extends AbstractId
{
    /** @var string */
    private $ulid;

    public function __construct(string $ulid = null)
    {
        if ($ulid !== null && static::isValid($ulid) === false) {
            throw new InvalidUlidStringException($ulid);
        }

        $this->ulid = $ulid ?? UlidGenerator::generate();
    }

    public function toScalar(): string
    {
        return (string) $this;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return $this->ulid;
    }

    /**
     * @param static $object
     */
    public function equals($object): bool
    {
        return $this->ulid === $object->toScalar();
    }

    /**
     * @param mixed $value
     */
    public static function isValid($value): bool
    {
        return is_string($value) && UlidGenerator::validate($value);
    }
}
