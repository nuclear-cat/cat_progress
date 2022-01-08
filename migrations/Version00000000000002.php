<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version00000000000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $statuses = [
            'active',
            'wait',
            'banned',
        ];

        $this->addSql("CREATE TYPE user_status AS ENUM ('" . implode("', '", $statuses) . "')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TYPE user_status');

    }
}
