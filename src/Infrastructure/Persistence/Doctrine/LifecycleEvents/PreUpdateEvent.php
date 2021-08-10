<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\LifecycleEvents;

final class PreUpdateEvent
{
    public const METHOD_NAME = 'touch';
    public const NAME = 'preUpdate';
}
