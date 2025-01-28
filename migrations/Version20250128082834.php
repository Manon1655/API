<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128082834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pret (id INT AUTO_INCREMENT NOT NULL, adherent_id INT DEFAULT NULL, livre_id INT DEFAULT NULL, date_pret DATE NOT NULL, date_retour_prevue DATE NOT NULL, date_retour_reelle DATE NOT NULL, INDEX IDX_52ECE97925F06C53 (adherent_id), INDEX IDX_52ECE97937D925CB (livre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97925F06C53 FOREIGN KEY (adherent_id) REFERENCES adherent (id)');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97925F06C53');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97937D925CB');
        $this->addSql('DROP TABLE pret');
    }
}
