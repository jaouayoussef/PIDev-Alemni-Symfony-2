<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228122439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP INDEX UNIQ_C11D7DD11521FA90, ADD INDEX IDX_C11D7DD11521FA90 (p_domaine_id)');
        $this->addSql('ALTER TABLE promotion DROP INDEX UNIQ_C11D7DD171F7E88B, ADD INDEX IDX_C11D7DD171F7E88B (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP INDEX IDX_C11D7DD11521FA90, ADD UNIQUE INDEX UNIQ_C11D7DD11521FA90 (p_domaine_id)');
        $this->addSql('ALTER TABLE promotion DROP INDEX IDX_C11D7DD171F7E88B, ADD UNIQUE INDEX UNIQ_C11D7DD171F7E88B (event_id)');
    }
}
