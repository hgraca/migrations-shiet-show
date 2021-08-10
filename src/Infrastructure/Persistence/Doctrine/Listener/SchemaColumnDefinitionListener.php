<?php

declare(strict_types=1);

namespace Acme\App\Infrastructure\Persistence\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Event\SchemaColumnDefinitionEventArgs;
use Doctrine\DBAL\Events;

final class SchemaColumnDefinitionListener implements EventSubscriber
{
    /** @var array<string, array<array-key, string>> */
    private $ignoredColumns;

    /**
     * @param array<string, array<array-key, string>> $doctrineMigrationsIgnoredColumns
     */
    public function __construct(array $doctrineMigrationsIgnoredColumns)
    {
        $this->ignoredColumns = $doctrineMigrationsIgnoredColumns;
    }

    public function onSchemaColumnDefinition(SchemaColumnDefinitionEventArgs $eventArgs): void
    {
        $tableName = $eventArgs->getTable();
        /** @var string $columnName */
        $columnName = $eventArgs->getTableColumn()['Field'];
        if (
            array_key_exists($tableName, $this->ignoredColumns)
            && in_array($columnName, $this->ignoredColumns[$tableName])
        ) {
            $eventArgs->preventDefault();
        }
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onSchemaColumnDefinition,
        ];
    }
}
