<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220930085035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, profil_recruteur_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_CB988C6FCD8E2678 (profil_recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidats_annonces (id INT AUTO_INCREMENT NOT NULL, profil_candidat_id INT DEFAULT NULL, annonces_id INT DEFAULT NULL, validé TINYINT(1) NOT NULL, INDEX IDX_40763F1352EEF2C2 (profil_candidat_id), INDEX IDX_40763F134C2885D7 (annonces_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_candidat (id INT AUTO_INCREMENT NOT NULL, tel VARCHAR(255) DEFAULT NULL, age VARCHAR(255) DEFAULT NULL, cv VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, rue VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_recruteur (id INT AUTO_INCREMENT NOT NULL, entreprise VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, profl_candidatid_id INT DEFAULT NULL, profil_recruteur_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, profil VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649BD8CF3F7 (profl_candidatid_id), UNIQUE INDEX UNIQ_8D93D649CD8E2678 (profil_recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FCD8E2678 FOREIGN KEY (profil_recruteur_id) REFERENCES profil_recruteur (id)');
        $this->addSql('ALTER TABLE candidats_annonces ADD CONSTRAINT FK_40763F1352EEF2C2 FOREIGN KEY (profil_candidat_id) REFERENCES profil_candidat (id)');
        $this->addSql('ALTER TABLE candidats_annonces ADD CONSTRAINT FK_40763F134C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649BD8CF3F7 FOREIGN KEY (profl_candidatid_id) REFERENCES profil_candidat (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649CD8E2678 FOREIGN KEY (profil_recruteur_id) REFERENCES profil_recruteur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FCD8E2678');
        $this->addSql('ALTER TABLE candidats_annonces DROP FOREIGN KEY FK_40763F1352EEF2C2');
        $this->addSql('ALTER TABLE candidats_annonces DROP FOREIGN KEY FK_40763F134C2885D7');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649BD8CF3F7');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CD8E2678');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE candidats_annonces');
        $this->addSql('DROP TABLE profil_candidat');
        $this->addSql('DROP TABLE profil_recruteur');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
