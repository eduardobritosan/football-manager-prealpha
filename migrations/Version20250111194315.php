<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111194315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__manager AS SELECT nif, highest_license FROM manager');
        $this->addSql('DROP TABLE manager');
        $this->addSql('CREATE TABLE manager (nif VARCHAR(9) NOT NULL, club_id INTEGER DEFAULT NULL, highest_license VARCHAR(255) DEFAULT NULL, PRIMARY KEY(nif), CONSTRAINT FK_FA2425B9ADE62BBB FOREIGN KEY (nif) REFERENCES employee (nif) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FA2425B961190A32 FOREIGN KEY (club_id) REFERENCES club (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO manager (nif, highest_license) SELECT nif, highest_license FROM __temp__manager');
        $this->addSql('DROP TABLE __temp__manager');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA2425B961190A32 ON manager (club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__manager AS SELECT nif, highest_license FROM manager');
        $this->addSql('DROP TABLE manager');
        $this->addSql('CREATE TABLE manager (nif VARCHAR(9) NOT NULL, highest_license VARCHAR(255) DEFAULT NULL, PRIMARY KEY(nif), CONSTRAINT FK_FA2425B9ADE62BBB FOREIGN KEY (nif) REFERENCES employee (nif) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO manager (nif, highest_license) SELECT nif, highest_license FROM __temp__manager');
        $this->addSql('DROP TABLE __temp__manager');
    }
}
