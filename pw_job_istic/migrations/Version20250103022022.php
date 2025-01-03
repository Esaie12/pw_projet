<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103022022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_favorites (developer_id INT NOT NULL, job_posting_id INT NOT NULL, INDEX IDX_E0F32C0464DD9267 (developer_id), INDEX IDX_E0F32C04F09E15EB (job_posting_id), PRIMARY KEY(developer_id, job_posting_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C0464DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_favorites ADD CONSTRAINT FK_E0F32C04F09E15EB FOREIGN KEY (job_posting_id) REFERENCES job_posting (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C0464DD9267');
        $this->addSql('ALTER TABLE developer_favorites DROP FOREIGN KEY FK_E0F32C04F09E15EB');
        $this->addSql('DROP TABLE developer_favorites');
    }
}
