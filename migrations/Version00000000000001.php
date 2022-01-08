<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00000000000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $weekdays = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        $this->addSql("CREATE TYPE progress_weekday AS ENUM ('" . implode("', '", $weekdays) . "')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TYPE progress_weekday');

    }
}
