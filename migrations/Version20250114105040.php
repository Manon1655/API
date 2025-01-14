<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114105040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB140EAB5DEB');
        $this->addSql('DROP INDEX IDX_55AB140EAB5DEB ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE many_to_one_id relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB1403256915B FOREIGN KEY (relation_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_55AB1403256915B ON auteur (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB1403256915B');
        $this->addSql('DROP INDEX IDX_55AB1403256915B ON auteur');
        $this->addSql('ALTER TABLE auteur CHANGE relation_id many_to_one_id INT NOT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB140EAB5DEB FOREIGN KEY (many_to_one_id) REFERENCES nationalite (id)');
        $this->addSql('CREATE INDEX IDX_55AB140EAB5DEB ON auteur (many_to_one_id)');
    }
}
