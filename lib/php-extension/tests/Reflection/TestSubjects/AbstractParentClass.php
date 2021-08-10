<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection\TestSubjects;

abstract class AbstractParentClass
{
    /** @var string */
    private $parentPrivate;

    /** @var string */
    protected $parentProtected;

    /** @var ?string */
    public $parentPublic;

    /** @var string */
    private static $parentPrivateStatic;

    public function __construct(
        string $parentPrivate,
        string $parentProtected,
        ?string $parentPublic,
        string $parentPrivateStatic
    ) {
        $this->parentPrivate = $parentPrivate;
        $this->parentProtected = $parentProtected;
        $this->parentPublic = $parentPublic;
        self::$parentPrivateStatic = $parentPrivateStatic;
    }

    public function getParentPrivate(): string
    {
        return $this->parentPrivate;
    }

    public function getParentProtected(): string
    {
        return $this->parentProtected;
    }

    public function getParentPublic(): ?string
    {
        return $this->parentPublic;
    }

    public function getParentPrivateStatic(): string
    {
        return self::$parentPrivateStatic;
    }
}
