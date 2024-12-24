<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223075501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, type_user VARCHAR(5) NOT NULL, name_society VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql("
        INSERT INTO `user` (email, type_user, roles, password) VALUES
        ('admin@gmail.com', 'admin', '[]', '\$2y\$13\$VQdXy.6Xx1vLsdPOAAVOwONpW53r6uEAXS5PqdI5JZl5RUuOIB6Fm'),
        ('society@gmail.com', 'society', '[]', '\$2y\$13\$OZfTmEOxOm7.uY.4GoBzV.sOSHVmvR/UYhr64TmrNzgAKKOHhNNK6'),
        ('dev@gmail.com', 'dev', '[]', '\$2y\$13\$wCYYErR1fn8BFNpJZrSKCuzG9RFGzmfiJevPP3zCNiK2MvFsAClLa')
    ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `user`');
    }
}
