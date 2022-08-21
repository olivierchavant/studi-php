<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220821143550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_candidat_annonces (profil_candidat_id INT NOT NULL, annonces_id INT NOT NULL, INDEX IDX_9869519452EEF2C2 (profil_candidat_id), INDEX IDX_986951944C2885D7 (annonces_id), PRIMARY KEY(profil_candidat_id, annonces_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE profil_candidat_annonces ADD CONSTRAINT FK_9869519452EEF2C2 FOREIGN KEY (profil_candidat_id) REFERENCES profil_candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_candidat_annonces ADD CONSTRAINT FK_986951944C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_candidat_annonces DROP FOREIGN KEY FK_9869519452EEF2C2');
        $this->addSql('ALTER TABLE profil_candidat_annonces DROP FOREIGN KEY FK_986951944C2885D7');
        $this->addSql('DROP TABLE profil_candidat_annonces');
    }
}
