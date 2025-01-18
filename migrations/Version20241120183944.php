<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120183944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cyclic_payment (id SERIAL NOT NULL, payment_id INT DEFAULT NULL, days INT DEFAULT NULL, months INT DEFAULT NULL, years INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D3A379C4C3A3BB ON cyclic_payment (payment_id)');
        $this->addSql('ALTER TABLE cyclic_payment ADD CONSTRAINT FK_2D3A379C4C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cyclic_payment DROP CONSTRAINT FK_2D3A379C4C3A3BB');
        $this->addSql('DROP TABLE cyclic_payment');
    }
}
