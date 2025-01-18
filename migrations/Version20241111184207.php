<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111184207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payments ADD email_id INT NOT NULL');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_65D29B32A832C1C9 FOREIGN KEY (email_id) REFERENCES payment_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_65D29B32A832C1C9 ON payments (email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payments DROP CONSTRAINT FK_65D29B32A832C1C9');
        $this->addSql('DROP INDEX IDX_65D29B32A832C1C9');
        $this->addSql('ALTER TABLE payments DROP email_id');
    }
}
