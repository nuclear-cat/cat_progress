<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211212232216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE progress_categories (id UUID NOT NULL, user_id UUID NOT NULL, title VARCHAR(120) NOT NULL, description TEXT DEFAULT NULL, color VARCHAR(25) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFD44870A76ED395 ON progress_categories (user_id)');
        $this->addSql('COMMENT ON COLUMN progress_categories.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_categories.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_categories.color IS \'(DC2Type:progress_category_color)\'');
        $this->addSql('CREATE TABLE progress_habit_completions (id UUID NOT NULL, habit_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, completion_percentage SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CD5A763E7AEB3B2 ON progress_habit_completions (habit_id)');
        $this->addSql('COMMENT ON COLUMN progress_habit_completions.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habit_completions.habit_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habit_completions.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN progress_habit_completions.completed_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE progress_habit_weekdays (id UUID NOT NULL, habit_id UUID NOT NULL, weekday progress_weekday NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9615EC80E7AEB3B2 ON progress_habit_weekdays (habit_id)');
        $this->addSql('CREATE UNIQUE INDEX progress_habit_weekdays_weekday_habit_id ON progress_habit_weekdays (weekday, habit_id)');
        $this->addSql('COMMENT ON COLUMN progress_habit_weekdays.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habit_weekdays.habit_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habit_weekdays.weekday IS \'(DC2Type:progress_weekday)\'');
        $this->addSql('CREATE TABLE progress_habits (id UUID NOT NULL, category_id UUID DEFAULT NULL, title VARCHAR(120) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, started_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14A870ED12469DE2 ON progress_habits (category_id)');
        $this->addSql('COMMENT ON COLUMN progress_habits.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habits.category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_habits.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN progress_habits.started_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE progress_tasks (id UUID NOT NULL, category_id UUID DEFAULT NULL, user_id UUID NOT NULL, title VARCHAR(120) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, completed_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, completion_percentage SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2E096AC12469DE2 ON progress_tasks (category_id)');
        $this->addSql('CREATE INDEX IDX_B2E096ACA76ED395 ON progress_tasks (user_id)');
        $this->addSql('COMMENT ON COLUMN progress_tasks.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_tasks.category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_tasks.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN progress_tasks.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN progress_tasks.completed_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE progress_users (id UUID NOT NULL, name VARCHAR(120) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN progress_users.id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE user_change_email_requests (id UUID NOT NULL, user_id UUID NOT NULL, token VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_confirmed BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2466889A76ED395 ON user_change_email_requests (user_id)');
        $this->addSql('COMMENT ON COLUMN user_change_email_requests.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_change_email_requests.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_change_email_requests.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_confirm_requests (token VARCHAR(255) NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(token, user_id))');
        $this->addSql('CREATE INDEX IDX_FA21DC5DA76ED395 ON user_confirm_requests (user_id)');
        $this->addSql('COMMENT ON COLUMN user_confirm_requests.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE user_password_requests (token VARCHAR(255) NOT NULL, user_id UUID NOT NULL, is_confirmed BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(token, user_id))');
        $this->addSql('CREATE INDEX IDX_130DFFBAA76ED395 ON user_password_requests (user_id)');
        $this->addSql('CREATE INDEX user_password_requests_token_idx ON user_password_requests (token)');
        $this->addSql('COMMENT ON COLUMN user_password_requests.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_password_requests.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_refresh_sessions (id UUID NOT NULL, user_id UUID DEFAULT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, device_info VARCHAR(128) NOT NULL, is_revoked BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51CECE79A76ED395 ON user_refresh_sessions (user_id)');
        $this->addSql('COMMENT ON COLUMN user_refresh_sessions.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_refresh_sessions.user_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN user_refresh_sessions.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, email VARCHAR(120) DEFAULT NULL, password_hash VARCHAR(255) DEFAULT NULL, name VARCHAR(120) NOT NULL, timezone VARCHAR(64) NOT NULL, status user_status NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN users.email IS \'(DC2Type:user_email)\'');
        $this->addSql('COMMENT ON COLUMN users.status IS \'(DC2Type:user_status)\'');
        $this->addSql('ALTER TABLE progress_categories ADD CONSTRAINT FK_DFD44870A76ED395 FOREIGN KEY (user_id) REFERENCES progress_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE progress_habit_completions ADD CONSTRAINT FK_CD5A763E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES progress_habits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE progress_habit_weekdays ADD CONSTRAINT FK_9615EC80E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES progress_habits (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE progress_habits ADD CONSTRAINT FK_14A870ED12469DE2 FOREIGN KEY (category_id) REFERENCES progress_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE progress_tasks ADD CONSTRAINT FK_B2E096AC12469DE2 FOREIGN KEY (category_id) REFERENCES progress_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE progress_tasks ADD CONSTRAINT FK_B2E096ACA76ED395 FOREIGN KEY (user_id) REFERENCES progress_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_change_email_requests ADD CONSTRAINT FK_2466889A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_confirm_requests ADD CONSTRAINT FK_FA21DC5DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_password_requests ADD CONSTRAINT FK_130DFFBAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_refresh_sessions ADD CONSTRAINT FK_51CECE79A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE progress_habits DROP CONSTRAINT FK_14A870ED12469DE2');
        $this->addSql('ALTER TABLE progress_tasks DROP CONSTRAINT FK_B2E096AC12469DE2');
        $this->addSql('ALTER TABLE progress_habit_completions DROP CONSTRAINT FK_CD5A763E7AEB3B2');
        $this->addSql('ALTER TABLE progress_habit_weekdays DROP CONSTRAINT FK_9615EC80E7AEB3B2');
        $this->addSql('ALTER TABLE progress_categories DROP CONSTRAINT FK_DFD44870A76ED395');
        $this->addSql('ALTER TABLE progress_tasks DROP CONSTRAINT FK_B2E096ACA76ED395');
        $this->addSql('ALTER TABLE user_change_email_requests DROP CONSTRAINT FK_2466889A76ED395');
        $this->addSql('ALTER TABLE user_confirm_requests DROP CONSTRAINT FK_FA21DC5DA76ED395');
        $this->addSql('ALTER TABLE user_password_requests DROP CONSTRAINT FK_130DFFBAA76ED395');
        $this->addSql('ALTER TABLE user_refresh_sessions DROP CONSTRAINT FK_51CECE79A76ED395');
        $this->addSql('DROP TABLE progress_categories');
        $this->addSql('DROP TABLE progress_habit_completions');
        $this->addSql('DROP TABLE progress_habit_weekdays');
        $this->addSql('DROP TABLE progress_habits');
        $this->addSql('DROP TABLE progress_tasks');
        $this->addSql('DROP TABLE progress_users');
        $this->addSql('DROP TABLE user_change_email_requests');
        $this->addSql('DROP TABLE user_confirm_requests');
        $this->addSql('DROP TABLE user_password_requests');
        $this->addSql('DROP TABLE user_refresh_sessions');
        $this->addSql('DROP TABLE users');
    }
}
