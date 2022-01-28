<?php

declare(strict_types=1);

use Acme\App\Core\Component\Channel\Domain\ChannelStateEnum;
use Acme\App\Core\Component\Channel\Domain\ChannelType;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */
$builder = new ClassMetadataBuilder($metadata);

$builder->setTable('channel__channel')
    ->addField(
        'id',
        'channel_id',
        [
            'id' => true,
            'options' => [
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]
    )
    ->addField(
        'clientId',
        'client_id',
        [
            'nullable' => false,
        ]
    )
    ->createManyToOne('channelType', ChannelType::class)
        ->addJoinColumn($columnName = 'channel_type_id', $referencedColumnName = 'id', $nullable = false)
        ->build()
    ->addField(
        'config',
        'channel_config',
        [
            'nullable' => false,
        ]
    )
    ->addField(
        'stateEnum',
        'channel_state_enum',
        [
            'length' => 50,
            'nullable' => false,
            'default' => (string) ChannelStateEnum::disabled(),
        ]
    );
$metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
