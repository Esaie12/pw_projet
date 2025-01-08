<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250104231229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_view (id INT AUTO_INCREMENT NOT NULL, job_id INT NOT NULL, developer_id INT NOT NULL, viewed_at DATETIME NOT NULL, INDEX IDX_7931DDCFBE04EA9 (job_id), INDEX IDX_7931DDCF64DD9267 (developer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_view ADD CONSTRAINT FK_7931DDCFBE04EA9 FOREIGN KEY (job_id) REFERENCES job_posting (id)');
        $this->addSql('ALTER TABLE job_view ADD CONSTRAINT FK_7931DDCF64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_view DROP FOREIGN KEY FK_7931DDCFBE04EA9');
        $this->addSql('ALTER TABLE job_view DROP FOREIGN KEY FK_7931DDCF64DD9267');
        $this->addSql('DROP TABLE job_view');
    }
}
