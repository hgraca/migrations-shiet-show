<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection\TestSubjects;

final class DummyClass extends DummyClassParent
{
    /** @var int */
    private $var;

    /** @var string */
    private $testProperty = 'FooBar';

    /** @var int|null */
    private $anotherVar;

    public function __construct(int $var = 1, int $parentVar = 2)
    {
        parent::__construct($parentVar);
        $this->var = $var;

        $this->anotherVar = 1;
    }

    public function getTestProperty(): string
    {
        return $this->testProperty;
    }

    public function getAnotherVar(): ?int
    {
        return $this->anotherVar;
    }

    protected function getVarProtected(): int
    {
        return $this->var;
    }

    private function getVarPrivate(): ?int
    {
        return $this->var;
    }
}
