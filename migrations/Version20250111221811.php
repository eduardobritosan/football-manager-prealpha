<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111221811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__employee AS SELECT nif, current_club_id, name, salary, type FROM employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('CREATE TABLE employee (nif VARCHAR(9) NOT NULL, current_club_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, salary NUMERIC(11, 2) DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(nif), CONSTRAINT FK_5D9F75A1CB148FB7 FOREIGN KEY (current_club_id) REFERENCES club (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO employee (nif, current_club_id, name, salary, type) SELECT nif, current_club_id, name, salary, type FROM __temp__employee');
        $this->addSql('DROP TABLE __temp__employee');
        $this->addSql('CREATE INDEX IDX_5D9F75A1CB148FB7 ON employee (current_club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee ADD COLUMN id INTEGER NOT NULL');
    }
}
