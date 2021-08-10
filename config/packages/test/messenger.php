<?php

declare(strict_types=1);

use Acme\App\Core\Port\CommandBus\CommandInterface;
use Acme\App\Core\Port\EventBus\EventInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'messenger' => [
            'routing' => [
                CommandInterface::class => 'sync',
                EventInterface::class => 'sync',
            ],
        ],
    ]);
};
