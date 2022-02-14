<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220213225451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion_code ADD cp_pcd_id INT DEFAULT NULL, DROP cp_pcd');
        $this->addSql('ALTER TABLE promotion_code ADD CONSTRAINT FK_C1EFB8072DEDD2B FOREIGN KEY (cp_pcd_id) REFERENCES promo_code_owner (id)');
        $this->addSql('CREATE INDEX IDX_C1EFB8072DEDD2B ON promotion_code (cp_pcd_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code_owner CHANGE pcd_name pcd_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_first_name pcd_first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_email pcd_email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_job pcd_job VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_gender pcd_gender VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_city pcd_city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pcd_note pcd_note VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE promotion CHANGE p_name p_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE p_domaine p_domaine VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE p_note p_note VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE promotion_code DROP FOREIGN KEY FK_C1EFB8072DEDD2B');
        $this->addSql('DROP INDEX IDX_C1EFB8072DEDD2B ON promotion_code');
        $this->addSql('ALTER TABLE promotion_code ADD cp_pcd INT NOT NULL, DROP cp_pcd_id, CHANGE pc_code pc_code VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pc_note pc_note VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
