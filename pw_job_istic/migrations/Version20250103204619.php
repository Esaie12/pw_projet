<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103204619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE society_favorites (society_id INT NOT NULL, developer_id INT NOT NULL, INDEX IDX_C322C72AE6389D24 (society_id), INDEX IDX_C322C72A64DD9267 (developer_id), PRIMARY KEY(society_id, developer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE society_favorites ADD CONSTRAINT FK_C322C72AE6389D24 FOREIGN KEY (society_id) REFERENCES society (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE society_favorites ADD CONSTRAINT FK_C322C72A64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE society_favorites DROP FOREIGN KEY FK_C322C72AE6389D24');
        $this->addSql('ALTER TABLE society_favorites DROP FOREIGN KEY FK_C322C72A64DD9267');
        $this->addSql('DROP TABLE society_favorites');
    }
}
