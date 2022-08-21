<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820172701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_recruteur (id INT AUTO_INCREMENT NOT NULL, entreprise VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, tel VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD profil_recruteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CD8E2678 FOREIGN KEY (profil_recruteur_id) REFERENCES profil_recruteur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649CD8E2678 ON user (profil_recruteur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649CD8E2678');
        $this->addSql('DROP TABLE profil_recruteur');
        $this->addSql('DROP INDEX UNIQ_8D93D649CD8E2678 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP profil_recruteur_id');
    }
}
