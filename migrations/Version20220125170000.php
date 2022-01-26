<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220125170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $types = [
            'success',
            'partially',
            'lazy',
            'alternative',
            'failed',
        ];

        $this->addSql("CREATE TYPE progress_habit_completion_type AS ENUM ('" . implode("', '", $types) . "')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TYPE progress_habit_completion_type');
    }
}
