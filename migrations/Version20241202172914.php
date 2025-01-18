<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202172914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_plan (id SERIAL NOT NULL, payments_id INT DEFAULT NULL, payment_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FCD9CC09BBC61482 ON payment_plan (payments_id)');
        $this->addSql('ALTER TABLE payment_plan ADD CONSTRAINT FK_FCD9CC09BBC61482 FOREIGN KEY (payments_id) REFERENCES payments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_plan DROP CONSTRAINT FK_FCD9CC09BBC61482');
        $this->addSql('DROP TABLE payment_plan');
    }
}
