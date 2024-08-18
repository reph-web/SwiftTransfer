<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240817195606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction_group DROP FOREIGN KEY FK_CCF394852FC0CB0F');
        $this->addSql('ALTER TABLE transaction_group DROP FOREIGN KEY FK_CCF39485FE54D947');
        $this->addSql('DROP TABLE transaction_group');
        $this->addSql('ALTER TABLE billing DROP INDEX UNIQ_EC224CAAA76ED395, ADD INDEX IDX_EC224CAAA76ED395 (user_id)');
        $this->addSql('ALTER TABLE billing ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP timestamp');
        $this->addSql('ALTER TABLE notification DROP is_read');
        $this->addSql('ALTER TABLE transaction ADD related_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D158D797EA FOREIGN KEY (related_group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_723705D158D797EA ON transaction (related_group_id)');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction_group (transaction_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_CCF394852FC0CB0F (transaction_id), INDEX IDX_CCF39485FE54D947 (group_id), PRIMARY KEY(transaction_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE transaction_group ADD CONSTRAINT FK_CCF394852FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_group ADD CONSTRAINT FK_CCF39485FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE billing DROP INDEX IDX_EC224CAAA76ED395, ADD UNIQUE INDEX UNIQ_EC224CAAA76ED395 (user_id)');
        $this->addSql('ALTER TABLE billing ADD timestamp DATETIME NOT NULL, DROP created_at');
        $this->addSql('ALTER TABLE notification ADD is_read TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D158D797EA');
        $this->addSql('DROP INDEX IDX_723705D158D797EA ON transaction');
        $this->addSql('ALTER TABLE transaction DROP related_group_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
