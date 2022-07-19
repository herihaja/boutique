<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719210730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_user_auth_group (auth_user_id INT UNSIGNED NOT NULL, auth_group_id INT UNSIGNED NOT NULL, INDEX IDX_C3225861E94AF366 (auth_user_id), INDEX IDX_C3225861DAAE5EC1 (auth_group_id), PRIMARY KEY(auth_user_id, auth_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auth_user_auth_group ADD CONSTRAINT FK_C3225861E94AF366 FOREIGN KEY (auth_user_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_user_auth_group ADD CONSTRAINT FK_C3225861DAAE5EC1 FOREIGN KEY (auth_group_id) REFERENCES auth_group (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE auth_group_auth_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_group_auth_user (auth_group_id INT UNSIGNED NOT NULL, auth_user_id INT UNSIGNED NOT NULL, INDEX IDX_A6A1E121DAAE5EC1 (auth_group_id), INDEX IDX_A6A1E121E94AF366 (auth_user_id), PRIMARY KEY(auth_group_id, auth_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE auth_group_auth_user ADD CONSTRAINT FK_A6A1E121DAAE5EC1 FOREIGN KEY (auth_group_id) REFERENCES auth_group (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_group_auth_user ADD CONSTRAINT FK_A6A1E121E94AF366 FOREIGN KEY (auth_user_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE auth_user_auth_group');
    }
}
