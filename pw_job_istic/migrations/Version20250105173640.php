<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105173640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_view (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, society_id INT NOT NULL, viewed_at DATETIME NOT NULL, INDEX IDX_A32DD6CD64DD9267 (developer_id), INDEX IDX_A32DD6CDE6389D24 (society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_view ADD CONSTRAINT FK_A32DD6CD64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE developer_view ADD CONSTRAINT FK_A32DD6CDE6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_view DROP FOREIGN KEY FK_A32DD6CD64DD9267');
        $this->addSql('ALTER TABLE developer_view DROP FOREIGN KEY FK_A32DD6CDE6389D24');
        $this->addSql('DROP TABLE developer_view');
    }
}
