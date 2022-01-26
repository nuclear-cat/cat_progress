<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126183237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE progress_projects (id UUID NOT NULL, creator_id UUID NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8E8630F761220EA6 ON progress_projects (creator_id)');
        $this->addSql('COMMENT ON COLUMN progress_projects.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_projects.creator_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE progress_projects ADD CONSTRAINT FK_8E8630F761220EA6 FOREIGN KEY (creator_id) REFERENCES progress_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.timezone IS \'(DC2Type:auth_user_timezone)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE progress_projects');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN users.timezone IS NULL');
    }
}
