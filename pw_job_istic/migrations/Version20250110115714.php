<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110115714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, rated_dev_id INT NOT NULL, rated_by_id INT NOT NULL, rating INT NOT NULL, review LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_D88926225F7710F9 (rated_dev_id), INDEX IDX_D8892622284C0C5B (rated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926225F7710F9 FOREIGN KEY (rated_dev_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622284C0C5B FOREIGN KEY (rated_by_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926225F7710F9');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622284C0C5B');
        $this->addSql('DROP TABLE rating');
    }
}
