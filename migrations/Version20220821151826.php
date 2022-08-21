<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220821151826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidats_annonces ADD profil_candidat_id INT DEFAULT NULL, ADD annonces_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE candidats_annonces ADD CONSTRAINT FK_40763F1352EEF2C2 FOREIGN KEY (profil_candidat_id) REFERENCES profil_candidat (id)');
        $this->addSql('ALTER TABLE candidats_annonces ADD CONSTRAINT FK_40763F134C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id)');
        $this->addSql('CREATE INDEX IDX_40763F1352EEF2C2 ON candidats_annonces (profil_candidat_id)');
        $this->addSql('CREATE INDEX IDX_40763F134C2885D7 ON candidats_annonces (annonces_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidats_annonces DROP FOREIGN KEY FK_40763F1352EEF2C2');
        $this->addSql('ALTER TABLE candidats_annonces DROP FOREIGN KEY FK_40763F134C2885D7');
        $this->addSql('DROP INDEX IDX_40763F1352EEF2C2 ON candidats_annonces');
        $this->addSql('DROP INDEX IDX_40763F134C2885D7 ON candidats_annonces');
        $this->addSql('ALTER TABLE candidats_annonces DROP profil_candidat_id, DROP annonces_id');
    }
}
