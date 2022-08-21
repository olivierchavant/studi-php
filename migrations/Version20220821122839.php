<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220821122839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, profil_recruteur_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, domaine VARCHAR(255) NOT NULL, salaire VARCHAR(255) NOT NULL, date_publication DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_CB988C6FCD8E2678 (profil_recruteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FCD8E2678 FOREIGN KEY (profil_recruteur_id) REFERENCES profil_recruteur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FCD8E2678');
        $this->addSql('DROP TABLE annonces');
    }
}
