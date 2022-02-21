<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221224945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE userresult (id INT AUTO_INCREMENT NOT NULL, id_quizz INT NOT NULL, id_user INT NOT NULL, result INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE userresult');
        $this->addSql('ALTER TABLE question CHANGE libelle libelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reponse1 reponse1 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reponse2 reponse2 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reponse3 reponse3 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE reponse4 reponse4 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE quiz CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
