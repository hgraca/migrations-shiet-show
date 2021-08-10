<?php

declare(strict_types=1);

use Acme\App\Core\Port\CommandBus\CommandHandlerInterface;
use Acme\App\Core\Port\CommandBus\CommandValidatorInterface;
use Acme\App\Core\Port\EventBus\EventListenerInterface;
use Acme\App\Core\Port\Persistence\Mutator\MutatorHandlerInterface;
use Acme\App\Core\Port\Persistence\Query\QueryHandlerInterface;
use Acme\App\Infrastructure\EventBus\Symfony\RegisterListenersForEventCompilerPass;
use Acme\PhpExtension\Filesystem\FilesystemInterface;
use Acme\PhpExtension\Filesystem\LocalFilesystem;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container): void {
    $services = $container
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->bind('$doctrineMigrationsIgnoredColumns', '%doctrine.migrations.ignored_columns%');
    $services->bind('$doctrineMigrationsIgnoredIndexes', '%doctrine.migrations.ignored_indexes%');

    $services->load('Acme\\App\\', dirname(__DIR__, 2) . '/src')
        ->exclude('%kernel.project_dir%/src/**/{Entity,*Enum.php,*ValueObject.php,*Dto.php,*ViewModel.php}');
};
