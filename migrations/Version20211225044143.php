<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225044143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE progress_habits ADD user_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN progress_habits.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE progress_habits ADD CONSTRAINT FK_14A870EDA76ED395 FOREIGN KEY (user_id) REFERENCES progress_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_14A870EDA76ED395 ON progress_habits (user_id)');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE progress_habits DROP CONSTRAINT FK_14A870EDA76ED395');
        $this->addSql('DROP INDEX IDX_14A870EDA76ED395');
        $this->addSql('ALTER TABLE progress_habits DROP user_id');
        $this->addSql('ALTER TABLE users ALTER timezone TYPE VARCHAR(64)');
        $this->addSql('ALTER TABLE users ALTER timezone DROP DEFAULT');
    }
}
