<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240402093718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create event table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE event (
            uuid VARCHAR(255) NOT NULL,
            aggregate_type VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            payload LONGTEXT NOT NULL,
            created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event');
    }
}
