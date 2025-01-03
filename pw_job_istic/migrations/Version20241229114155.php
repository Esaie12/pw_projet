<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229114155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developer_langages (developer_id INT NOT NULL, langage_id INT NOT NULL, INDEX IDX_88A5FE9A64DD9267 (developer_id), INDEX IDX_88A5FE9A957BB53C (langage_id), PRIMARY KEY(developer_id, langage_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE developer_langages ADD CONSTRAINT FK_88A5FE9A64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE developer_langages ADD CONSTRAINT FK_88A5FE9A957BB53C FOREIGN KEY (langage_id) REFERENCES langage (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE developer_langages DROP FOREIGN KEY FK_88A5FE9A64DD9267');
        $this->addSql('ALTER TABLE developer_langages DROP FOREIGN KEY FK_88A5FE9A957BB53C');
        $this->addSql('DROP TABLE developer_langages');
    }
}
