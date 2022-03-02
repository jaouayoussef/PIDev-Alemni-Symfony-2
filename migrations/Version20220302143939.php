<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302143939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_event ADD user_id_id INT NOT NULL, ADD event_id_id INT NOT NULL, DROP prix_reservation_event, DROP user_id, DROP event_id');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA009D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA003E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_78D1DA009D86650F ON reservation_event (user_id_id)');
        $this->addSql('CREATE INDEX IDX_78D1DA003E5F2F7B ON reservation_event (event_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA009D86650F');
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA003E5F2F7B');
        $this->addSql('DROP INDEX IDX_78D1DA009D86650F ON reservation_event');
        $this->addSql('DROP INDEX IDX_78D1DA003E5F2F7B ON reservation_event');
        $this->addSql('ALTER TABLE reservation_event ADD prix_reservation_event VARCHAR(255) NOT NULL, ADD user_id INT NOT NULL, ADD event_id INT NOT NULL, DROP user_id_id, DROP event_id_id');
    }
}
