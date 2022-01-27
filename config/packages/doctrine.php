<?php

declare(strict_types=1);

use Acme\App\Infrastructure\Persistence\Doctrine\Type\ChannelConfigType;
use Acme\App\Infrastructure\Persistence\Doctrine\Type\ChannelIdType;
use Acme\App\Infrastructure\Persistence\Doctrine\Type\ChannelStateEnumType;
use Acme\App\Infrastructure\Persistence\Doctrine\Type\ChannelTypeEnumType;
use Acme\App\Infrastructure\Persistence\Doctrine\Type\ChannelTypeIdType;
use Acme\App\Infrastructure\Persistence\Doctrine\Type\ClientIdType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/** @var string[] $ignoredTables */
$ignoredTables = require __DIR__ . '/doctrine_migrations_ignored_tables.php';

return function (ContainerConfigurator $container) use ($ignoredTables): void {
    $container->extension('doctrine', [
        'dbal' => [
            'url' => '%env(resolve:DATABASE_URL)%',
//            'charset' => 'utf8mb4',
//            'default_table_options' => [
//                'engine' => 'InnoDB',
//                'charset' => 'utf8mb4',
//                'collate' => 'utf8mb4_unicode_ci',
//            ],
            # the DBAL driverOptions option
//            'options' => [
//                'charset' => 'utf8mb4', // characterset ?
//                'collate' => 'utf8mb4_unicode_ci', // collation ?
//            ],
            'mapping_types' => [
                'enum' => 'string',
            ],
            'types' => [
                'channel_id' => ChannelIdType::class,
                'channel_config' => ChannelConfigType::class,
                'channel_state_enum' => ChannelStateEnumType::class,
                'channel_type_id' => ChannelTypeIdType::class,
                'channel_type_enum' => ChannelTypeEnumType::class,
                'client_id' => ClientIdType::class,
            ],
            // Only consider tables with names matching the pattern.
            // The pattern is negating (negative lookaround), so it will ignore any table in the list.

            'schema_filter' => $ignoredTables === [] ? null : '~^(?!' . implode('|', $ignoredTables) . ')~',
        ],
        'orm' => [
            'auto_generate_proxy_classes' => true,
            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
            'auto_mapping' => true,
            'mappings' => [
                'Acme\App\Core\Component' => [
                    'is_bundle' => false,
                    'type' => 'php',
                    'dir' => '%kernel.project_dir%/config/doctrine/mappings/components',
                    'prefix' => 'Acme\App\Core\Component',
                ],
            ],
        ],
    ]);
};
