<?php

declare(strict_types=1);

namespace <namespace>;

use Acme\App\Build\Migration\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

final class <className> extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<up>
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<down>
    }
}
