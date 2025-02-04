<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204092027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent ADD code_commune VARCHAR(255) DEFAULT NULL, ADD password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE editeur CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A747EF6C6E55B5 ON editeur (nom)');
        $this->addSql('ALTER TABLE livre CHANGE isbn isbn VARCHAR(17) NOT NULL, CHANGE titre titre VARCHAR(100) NOT NULL, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE annee annee INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC634F99CC1CF4E6 ON livre (isbn)');
        $this->addSql('ALTER TABLE nationalite CHANGE libelle libelle VARCHAR(50) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9EC4D73FA4D60759 ON nationalite (libelle)');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97937D925CB');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adherent DROP code_commune, DROP password');
        $this->addSql('DROP INDEX UNIQ_5A747EF6C6E55B5 ON editeur');
        $this->addSql('ALTER TABLE editeur CHANGE nom nom VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_AC634F99CC1CF4E6 ON livre');
        $this->addSql('ALTER TABLE livre CHANGE isbn isbn VARCHAR(255) NOT NULL, CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE prix prix DOUBLE PRECISION DEFAULT NULL, CHANGE annee annee INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_9EC4D73FA4D60759 ON nationalite');
        $this->addSql('ALTER TABLE nationalite CHANGE libelle libelle VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pret DROP FOREIGN KEY FK_52ECE97937D925CB');
        $this->addSql('ALTER TABLE pret ADD CONSTRAINT FK_52ECE97937D925CB FOREIGN KEY (livre_id) REFERENCES livre (id) ON UPDATE CASCADE');
    }
}
