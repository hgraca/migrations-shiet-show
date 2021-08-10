<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity;

use Acme\PhpExtension\EqualityInterface;
use Acme\PhpExtension\ScalarObjectInterface;
use JsonSerializable;
use function get_class;

/**
 * Maybe we could avoid having this class and the inheritance tree here, but I find it nice because of the validation
 * it encapsulates, because of the global type hinting it provides, and also just as an example.
 */
abstract class AbstractId implements JsonSerializable, ScalarObjectInterface, EqualityInterface
{
    /** @var mixed */
    protected $id;

    public function __construct($id)
    {
        $this->validate($id);

        $this->id = $id;
    }

    /**
     * This is an example of the Template pattern, where this method is defined (templated) and used here,
     * but implemented in a subclass.
     */
    abstract protected static function isValid($value): bool;

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function jsonSerialize()
    {
        return $this->id;
    }

    /**
     * @param static $object
     */
    public function equals($object): bool
    {
        return get_class($this) === get_class($object)
            && $this->id === $object->id;
    }

    protected function validate($id): void
    {
        if (!static::isValid($id)) {
            throw new InvalidIdException($id);
        }
    }
}
