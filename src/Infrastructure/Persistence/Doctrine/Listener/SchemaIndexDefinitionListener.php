<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Event\SchemaIndexDefinitionEventArgs;
use Doctrine\DBAL\Events;

final class SchemaIndexDefinitionListener implements EventSubscriber
{
    /** @var array<string, array<array-key, string>> */
    private $ignoredIndexes;

    /**
     * @param array<string, array<array-key, string>> $doctrineMigrationsIgnoredIndexes
     */
    public function __construct(array $doctrineMigrationsIgnoredIndexes)
    {
        $this->ignoredIndexes = $doctrineMigrationsIgnoredIndexes;
    }

    public function onSchemaIndexDefinition(SchemaIndexDefinitionEventArgs $eventArgs): void
    {
        $tableName = $eventArgs->getTable();
        /** @var string $indexName */
        $indexName = $eventArgs->getTableIndex()['name'];
        if (
            array_key_exists($tableName, $this->ignoredIndexes)
            && in_array($indexName, $this->ignoredIndexes[$tableName])
        ) {
            $eventArgs->preventDefault();
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onSchemaIndexDefinition,
        ];
    }
}
