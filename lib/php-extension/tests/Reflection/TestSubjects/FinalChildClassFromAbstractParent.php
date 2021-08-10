<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Reflection\TestSubjects;

final class FinalChildClassFromAbstractParent extends AbstractParentClass
{
    /** @var string */
    private $childPrivate;

    /** @var string */
    protected $childProtected;

    /** @var string */
    public $childPublic;

    /** @var ?string */
    private static $childPrivateStatic;

    public function __construct(
        string $parentPrivate = '1',
        string $parentProtected = '2',
        string $parentPublic = '3',
        string $childPrivate = '4',
        string $childProtected = '5',
        string $childPublic = '6',
        string $parentPrivateStatic = '7',
        string $childPrivateStatic = '8'
    ) {
        parent::__construct($parentPrivate, $parentProtected, $parentPublic, $parentPrivateStatic);
        $this->childPrivate = $childPrivate;
        $this->childProtected = $childProtected;
        $this->childPublic = $childPublic;
        self::$childPrivateStatic = $childPrivateStatic;
    }

    public function getChildPrivate(): string
    {
        return $this->childPrivate;
    }

    public function getChildProtected(): string
    {
        return $this->childProtected;
    }

    public function getChildPublic(): string
    {
        return $this->childPublic;
    }

    public function getChildPrivateStatic(): ?string
    {
        return self::$childPrivateStatic;
    }
}
