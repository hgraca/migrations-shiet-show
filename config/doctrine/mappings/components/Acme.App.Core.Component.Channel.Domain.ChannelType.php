<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/** @var ClassMetadata $metadata */
$builder = new ClassMetadataBuilder($metadata);

$builder->setTable('channel__channel_type')
    ->addField(
        'id',
        'channel_type_id',
        ['id' => true]
    )
    ->addField(
        'name',
        'channel_type_enum',
        [
            'nullable' => false,
            'length' => 100,
        ]
    )
    ->addField(
        'enabled',
        'boolean',
        [
            'nullable' => false,
            'default' => 0,
        ]
    );
$metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
