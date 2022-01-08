<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213113401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progress_habit_completions ADD total_points SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE progress_habits ADD total_points SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE progress_habits DROP total_points');
        $this->addSql('ALTER TABLE progress_habit_completions DROP total_points');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
    }
}
