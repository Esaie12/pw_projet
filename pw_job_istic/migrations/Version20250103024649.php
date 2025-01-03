<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103031148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, color VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_7B00651C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, job_posting_id INT NOT NULL, availability_date DATE NOT NULL, motivation LONGTEXT DEFAULT NULL, INDEX IDX_6AB5B47164DD9267 (developer_id), INDEX IDX_6AB5B471F09E15EB (job_posting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B47164DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B471F09E15EB FOREIGN KEY (job_posting_id) REFERENCES job_posting (id)');

        $this->addSql('ALTER TABLE candidat ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B4716BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_6AB5B4716BF700BD ON candidat (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B47164DD9267');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B471F09E15EB');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B4716BF700BD');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_6AB5B4716BF700BD ON candidat');
        $this->addSql('ALTER TABLE candidat DROP status_id');
        $this->addSql('DROP TABLE candidat');
    }
}
