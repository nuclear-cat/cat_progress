<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220125175208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE progress_habit_completions ADD comment TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE progress_habit_completions ADD type progress_habit_completion_type NULL');
        $this->addSql('UPDATE progress_habit_completions SET type = \'success\' WHERE true');
        $this->addSql('ALTER TABLE progress_habit_completions ALTER COLUMN type SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN progress_habit_completions.type IS \'(DC2Type:progress_habit_completion_type)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE progress_habit_completions DROP comment');
        $this->addSql('ALTER TABLE progress_habit_completions DROP type');
    }
}
