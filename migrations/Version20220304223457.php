<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304223457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, nom_domaine VARCHAR(255) NOT NULL, description_domaine VARCHAR(255) NOT NULL, image_domaine VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, e_name VARCHAR(255) NOT NULL, e_photo VARCHAR(255) NOT NULL, e_note VARCHAR(255) DEFAULT NULL, e_place VARCHAR(255) NOT NULL, e_price INT NOT NULL, e_tel_number INT NOT NULL, e_email VARCHAR(255) NOT NULL, e_date_debut DATETIME NOT NULL, e_date_fin DATETIME NOT NULL, e_nbre INT NOT NULL, e_place_reserver INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, domaine_id INT NOT NULL, formateur_id INT NOT NULL, nom_formation VARCHAR(255) NOT NULL, description_formation VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, prix_formation DOUBLE PRECISION NOT NULL, image_formation VARCHAR(255) NOT NULL, nb_places INT NOT NULL, places_reserve INT NOT NULL, INDEX IDX_404021BF4272FC9F (domaine_id), INDEX IDX_404021BF155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_code_owner (id INT AUTO_INCREMENT NOT NULL, pcd_name VARCHAR(255) NOT NULL, pcd_first_name VARCHAR(255) NOT NULL, pcd_email VARCHAR(255) NOT NULL, pcd_job VARCHAR(255) NOT NULL, pcd_telephone_number NUMERIC(10, 0) NOT NULL, pcd_gender VARCHAR(255) NOT NULL, pcd_city VARCHAR(255) NOT NULL, pcd_note VARCHAR(255) DEFAULT NULL, pcd_nbre_promo INT NOT NULL, UNIQUE INDEX UNIQ_7FAE5F53EB5F299E (pcd_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, p_domaine_id INT DEFAULT NULL, event_id INT DEFAULT NULL, p_name VARCHAR(255) NOT NULL, p_value INT NOT NULL, p_date_b DATE NOT NULL, p_date_f DATE NOT NULL, p_note VARCHAR(255) DEFAULT NULL, INDEX IDX_C11D7DD11521FA90 (p_domaine_id), INDEX IDX_C11D7DD171F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_code (id INT AUTO_INCREMENT NOT NULL, cp_pcd_id INT NOT NULL, pc_code VARCHAR(255) NOT NULL, pc_value INT NOT NULL, pc_expiration_code DATE NOT NULL, pc_note VARCHAR(255) DEFAULT NULL, INDEX IDX_C1EFB8072DEDD2B (cp_pcd_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, reponse1 VARCHAR(255) NOT NULL, reponse2 VARCHAR(255) NOT NULL, reponse3 VARCHAR(255) NOT NULL, reponse4 VARCHAR(255) NOT NULL, repcorrect INT NOT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, id_formation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, id_user INT NOT NULL, UNIQUE INDEX UNIQ_A412FA9271C15D5C (id_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, user_file VARCHAR(255) DEFAULT NULL, sending_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, reclamation_id INT NOT NULL, answer VARCHAR(255) NOT NULL, admin_file VARCHAR(255) DEFAULT NULL, reply_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC72D6BA2D9 (reclamation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_event (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, event_id_id INT NOT NULL, prix_reservation_event VARCHAR(255) NOT NULL, date_reservation_event DATETIME NOT NULL, INDEX IDX_78D1DA009D86650F (user_id_id), INDEX IDX_78D1DA003E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_formation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, formation_id INT NOT NULL, prix DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX IDX_5A074020A76ED395 (user_id), INDEX IDX_5A0740205200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seance (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, nom_seance VARCHAR(255) NOT NULL, description_seance VARCHAR(255) NOT NULL, date_seance DATE NOT NULL, heure_deb TIME NOT NULL, heure_fin TIME NOT NULL, INDEX IDX_DF7DFD0E5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, is_banned TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, verification_file VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE useranswer (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_question INT NOT NULL, id_quiz INT NOT NULL, rep_correct INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE userresult (id INT AUTO_INCREMENT NOT NULL, id_quizz INT NOT NULL, id_user INT NOT NULL, result INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD11521FA90 FOREIGN KEY (p_domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD171F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE promotion_code ADD CONSTRAINT FK_C1EFB8072DEDD2B FOREIGN KEY (cp_pcd_id) REFERENCES promo_code_owner (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9271C15D5C FOREIGN KEY (id_formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC72D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA009D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_event ADD CONSTRAINT FK_78D1DA003E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE reservation_formation ADD CONSTRAINT FK_5A074020A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_formation ADD CONSTRAINT FK_5A0740205200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0E5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF4272FC9F');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD11521FA90');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD171F7E88B');
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA003E5F2F7B');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9271C15D5C');
        $this->addSql('ALTER TABLE reservation_formation DROP FOREIGN KEY FK_5A0740205200282E');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0E5200282E');
        $this->addSql('ALTER TABLE promotion_code DROP FOREIGN KEY FK_C1EFB8072DEDD2B');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC72D6BA2D9');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF155D8F51');
        $this->addSql('ALTER TABLE reservation_event DROP FOREIGN KEY FK_78D1DA009D86650F');
        $this->addSql('ALTER TABLE reservation_formation DROP FOREIGN KEY FK_5A074020A76ED395');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE promo_code_owner');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_code');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation_event');
        $this->addSql('DROP TABLE reservation_formation');
        $this->addSql('DROP TABLE seance');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE useranswer');
        $this->addSql('DROP TABLE userresult');
    }
}
