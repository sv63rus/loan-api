<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429182716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE loan_applications (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, amount INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL --(DC2Type:date_immutable)
            , end_date DATE NOT NULL --(DC2Type:date_immutable)
            , eligible BOOLEAN NOT NULL, rejection_reason VARCHAR(255) DEFAULT NULL, issued BOOLEAN NOT NULL, CONSTRAINT FK_86DC222619EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_86DC222619EB6921 ON loan_applications (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__loan AS SELECT id, client_id, name, amount, rate, start_date, end_date, approved FROM loan
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loan
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, amount INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, start_date DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , end_date DATETIME NOT NULL --(DC2Type:datetime_immutable)
            , approved BOOLEAN NOT NULL, CONSTRAINT FK_C5D30D0319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO loan (id, client_id, name, amount, rate, start_date, end_date, approved) SELECT id, client_id, name, amount, rate, start_date, end_date, approved FROM __temp__loan
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__loan
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5D30D0319EB6921 ON loan (client_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE loan_applications
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__loan AS SELECT id, client_id, name, amount, rate, start_date, end_date, approved FROM loan
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loan
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, client_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, amount INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, approved BOOLEAN NOT NULL, CONSTRAINT FK_C5D30D0319EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO loan (id, client_id, name, amount, rate, start_date, end_date, approved) SELECT id, client_id, name, amount, rate, start_date, end_date, approved FROM __temp__loan
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__loan
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C5D30D0319EB6921 ON loan (client_id)
        SQL);
    }
}
