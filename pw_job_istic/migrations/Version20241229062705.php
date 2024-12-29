<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229062705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, about_me VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, salary INT DEFAULT NULL, niveau_experience INT DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_65FB8B9AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_posting (id INT AUTO_INCREMENT NOT NULL, job_type_id INT NOT NULL, society_id INT NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, technologies JSON NOT NULL, experience_level INT NOT NULL, salary INT NOT NULL, description VARCHAR(3000) DEFAULT NULL, published_at DATE DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, pdf_file VARCHAR(255) DEFAULT NULL, INDEX IDX_27C8EAE85FA33B08 (job_type_id), INDEX IDX_27C8EAE8E6389D24 (society_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE langage (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE society (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, galleries JSON DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, creation_annee INT DEFAULT NULL, about LONGTEXT DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D6461F2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, type_user VARCHAR(10) NOT NULL, name_society VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job_posting ADD CONSTRAINT FK_27C8EAE85FA33B08 FOREIGN KEY (job_type_id) REFERENCES job_type (id)');
        $this->addSql('ALTER TABLE job_posting ADD CONSTRAINT FK_27C8EAE8E6389D24 FOREIGN KEY (society_id) REFERENCES society (id)');
        $this->addSql('ALTER TABLE society ADD CONSTRAINT FK_D6461F2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AA76ED395');
        $this->addSql('ALTER TABLE job_posting DROP FOREIGN KEY FK_27C8EAE85FA33B08');
        $this->addSql('ALTER TABLE job_posting DROP FOREIGN KEY FK_27C8EAE8E6389D24');
        $this->addSql('ALTER TABLE society DROP FOREIGN KEY FK_D6461F2A76ED395');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE job_posting');
        $this->addSql('DROP TABLE job_type');
        $this->addSql('DROP TABLE langage');
        $this->addSql('DROP TABLE society');
        $this->addSql('DROP TABLE `user`');
    }
}
