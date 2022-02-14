<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213184749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_code_owner (id INT AUTO_INCREMENT NOT NULL, pcd_name VARCHAR(255) NOT NULL, pcd_first_name VARCHAR(255) NOT NULL, pcd_email VARCHAR(255) NOT NULL, pcd_job VARCHAR(255) NOT NULL, pcd_telephone_number NUMERIC(10, 0) NOT NULL, pcd_gender VARCHAR(255) NOT NULL, pcd_city VARCHAR(255) NOT NULL, pcd_note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, p_name VARCHAR(255) NOT NULL, p_value INT NOT NULL, p_domaine VARCHAR(255) NOT NULL, p_date_b DATE NOT NULL, p_date_f DATE NOT NULL, p_note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_code (id INT AUTO_INCREMENT NOT NULL, cp_pcd INT NOT NULL, pc_code VARCHAR(255) NOT NULL, pc_value INT NOT NULL, pc_expiration_code DATE NOT NULL, pc_note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promo_code_owner');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_code');
    }
}
