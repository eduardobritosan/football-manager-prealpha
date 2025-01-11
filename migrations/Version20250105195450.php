<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105195450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__employee AS SELECT nif, id, name, salary FROM employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('CREATE TABLE employee (nif VARCHAR(9) NOT NULL, id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, salary NUMERIC(11, 2) DEFAULT NULL, club VARCHAR(255) DEFAULT NULL, PRIMARY KEY(nif))');
        $this->addSql('INSERT INTO employee (nif, id, name, salary) SELECT nif, id, name, salary FROM __temp__employee');
        $this->addSql('DROP TABLE __temp__employee');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__employee AS SELECT nif, id, name, salary FROM employee');
        $this->addSql('DROP TABLE employee');
        $this->addSql('CREATE TABLE employee (nif VARCHAR(9) NOT NULL, id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, salary NUMERIC(11, 2) NOT NULL, PRIMARY KEY(nif))');
        $this->addSql('INSERT INTO employee (nif, id, name, salary) SELECT nif, id, name, salary FROM __temp__employee');
        $this->addSql('DROP TABLE __temp__employee');
    }
}
