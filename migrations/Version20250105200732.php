<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105200732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, budget NUMERIC(11, 2) NOT NULL)');
        $this->addSql('CREATE TABLE manager (nif VARCHAR(9) NOT NULL, highest_license VARCHAR(255) DEFAULT NULL, PRIMARY KEY(nif), CONSTRAINT FK_FA2425B9ADE62BBB FOREIGN KEY (nif) REFERENCES employee (nif) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE TABLE player (nif VARCHAR(9) NOT NULL, release_clause NUMERIC(11, 2) DEFAULT NULL, PRIMARY KEY(nif), CONSTRAINT FK_98197A65ADE62BBB FOREIGN KEY (nif) REFERENCES employee (nif) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('ALTER TABLE employee ADD COLUMN type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE player');
        $this->addSql('CREATE TEMPORARY TABLE __temp__employee AS SELECT nif, id, name, salary, club FROM employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('CREATE TABLE employee (nif VARCHAR(9) NOT NULL, id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, salary NUMERIC(11, 2) DEFAULT NULL, club VARCHAR(255) DEFAULT NULL, PRIMARY KEY(nif))');
        $this->addSql('INSERT INTO employee (nif, id, name, salary, club) SELECT nif, id, name, salary, club FROM __temp__employee');
        $this->addSql('DROP TABLE __temp__employee');
    }
}
