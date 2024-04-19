<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417093323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', amount DOUBLE PRECISION NOT NULL, INDEX IDX_723705D1F624B39D (sender_id), INDEX IDX_723705D1CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction_group (transaction_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_CCF394852FC0CB0F (transaction_id), INDEX IDX_CCF39485FE54D947 (group_id), PRIMARY KEY(transaction_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction_group ADD CONSTRAINT FK_CCF394852FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_group ADD CONSTRAINT FK_CCF39485FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD balance DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F624B39D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1CD53EDB6');
        $this->addSql('ALTER TABLE transaction_group DROP FOREIGN KEY FK_CCF394852FC0CB0F');
        $this->addSql('ALTER TABLE transaction_group DROP FOREIGN KEY FK_CCF39485FE54D947');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE transaction_group');
        $this->addSql('ALTER TABLE user DROP balance');
    }
}
