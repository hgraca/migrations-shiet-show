<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection\TestSubjects;

class DummyClassParent
{
    /** @var int */
    private $parentVar;

    /** @var string */
    private $parentTestProperty = 'FooBar';

    public function __construct(int $parentVar)
    {
        $this->parentVar = $parentVar;
    }

    public function getParentTestProperty(): string
    {
        return $this->parentTestProperty;
    }
}
