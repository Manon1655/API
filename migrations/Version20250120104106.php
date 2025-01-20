<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120104106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE979794A6F1F');
        $this->addSql('DROP INDEX IDX_52ECE979794A6F1F ON pret');
        $this->addSql('ALTER TABLE pret CHANGE adh_id adherent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97925F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_52ECE97925F06C53 ON pret (adherent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97925F06C53');
        $this->addSql('DROP INDEX IDX_52ECE97925F06C53 ON pret');
        $this->addSql('ALTER TABLE pret CHANGE adherent_id adh_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE979794A6F1F FOREIGN KEY (adh_id) REFERENCES adherent (id)');
        $this->addSql('CREATE INDEX IDX_52ECE979794A6F1F ON pret (adh_id)');
    }
}
