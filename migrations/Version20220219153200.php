<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220219153200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD p_domaine_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD11521FA90 FOREIGN KEY (p_domaine_id) REFERENCES domaine (id)');
        $this->addSql('CREATE INDEX IDX_C11D7DD11521FA90 ON promotion (p_domaine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD11521FA90');
        $this->addSql('DROP INDEX IDX_C11D7DD11521FA90 ON promotion');
        $this->addSql('ALTER TABLE promotion DROP p_domaine_id');
    }
}
