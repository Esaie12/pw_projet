<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229064620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jobposting_technologies (job_posting_id INT NOT NULL, langage_id INT NOT NULL, INDEX IDX_E906B27AF09E15EB (job_posting_id), INDEX IDX_E906B27A957BB53C (langage_id), PRIMARY KEY(job_posting_id, langage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobposting_technologies ADD CONSTRAINT FK_E906B27AF09E15EB FOREIGN KEY (job_posting_id) REFERENCES job_posting (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobposting_technologies ADD CONSTRAINT FK_E906B27A957BB53C FOREIGN KEY (langage_id) REFERENCES langage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_posting DROP technologies');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobposting_technologies DROP FOREIGN KEY FK_E906B27AF09E15EB');
        $this->addSql('ALTER TABLE jobposting_technologies DROP FOREIGN KEY FK_E906B27A957BB53C');
        $this->addSql('DROP TABLE jobposting_technologies');
        $this->addSql('ALTER TABLE job_posting ADD technologies JSON NOT NULL');
    }
}
