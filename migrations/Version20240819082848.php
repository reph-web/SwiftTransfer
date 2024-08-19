<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240819082848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD related_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D158D797EA FOREIGN KEY (related_group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_723705D158D797EA ON transaction (related_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D158D797EA');
        $this->addSql('DROP INDEX IDX_723705D158D797EA ON transaction');
        $this->addSql('ALTER TABLE transaction DROP related_group_id');
    }
}
