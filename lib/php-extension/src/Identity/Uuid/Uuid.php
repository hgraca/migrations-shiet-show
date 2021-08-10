<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Uuid;

use Acme\PhpExtension\Identity\AbstractId;
use function is_string;

/**
 * This class could be final, and we could have all entities use this same ID class.
 * However, that would have little more benefit than simply having the entities IDs as simple UUID strings, we would
 * still have the ambiguity of "is this value supposed to be the ID of this entity, or is there a mistake and this is
 * some other value?".
 * What we really want to do is to have a specific ID class for each entity, so that we can type hint to specific
 * classes and remove all ambiguity.
 *
 * Therefore:
 * This class is to be used only as a generic UUID class.
 * For entities UUIDs, we should extend it and create a UUID class specific for that entity,
 * to take full advantage of typing.
 */
class Uuid extends AbstractId
{
    /** @var string */
    private $uuid;

    public function __construct(string $uuid = null)
    {
        if ($uuid !== null && !self::isValid($uuid)) {
            throw new InvalidUuidStringException($uuid);
        }

        $this->uuid = $uuid ?? UuidGenerator::generateAsString();
    }

    public function toScalar(): string
    {
        return (string) $this;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public static function isValid($value): bool
    {
        return is_string($value) && UuidGenerator::validate($value);
    }

    /**
     * @param static $object
     */
    public function equals($object): bool
    {
        return $this->uuid === $object->toScalar();
    }
}
