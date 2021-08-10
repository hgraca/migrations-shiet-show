<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity;

use function is_string;

/**
 * This is useful, for example when our application uses 3rd party systems that provide us with their own (string)
 * IDs, and which we need to store on our system.
 *
 * This class could be final instead of abstract, and we could have all entities use this same ID class.
 * However, that would have little more benefit than simply having the entities IDs as simple integers, we would still
 * have the ambiguity of "is this value supposed to be the ID of this entity, or is there a mistake and this is some
 * other value?".
 * What we really want to do is to have a specific ID class for each entity, so that we can type hint to specific
 * classes and remove all ambiguity.
 */
abstract class AbstractStringId extends AbstractId
{
    /** @var string */
    protected $id;

    /**
     * This constructor is here only to provide the type hint.
     */
    public function __construct(string $id)
    {
        parent::__construct($id);
    }

    public function toScalar(): string
    {
        return $this->id;
    }

    protected static function isValid($value): bool
    {
        return is_string($value);
    }
}
