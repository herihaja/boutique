<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220527133154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auth_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX auth_group_name_key (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_group_auth_user (auth_group_id INT UNSIGNED NOT NULL, auth_user_id INT UNSIGNED NOT NULL, INDEX IDX_A6A1E121DAAE5EC1 (auth_group_id), INDEX IDX_A6A1E121E94AF366 (auth_user_id), PRIMARY KEY(auth_group_id, auth_user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_group_auth_permission (auth_group_id INT UNSIGNED NOT NULL, auth_permission_id INT UNSIGNED NOT NULL, INDEX IDX_79D5E220DAAE5EC1 (auth_group_id), INDEX IDX_79D5E220F94AD0DA (auth_permission_id), PRIMARY KEY(auth_group_id, auth_permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_permission (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, codename VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametre (id INT UNSIGNED AUTO_INCREMENT NOT NULL, nom_parametre VARCHAR(254) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametre_valeur (id INT UNSIGNED AUTO_INCREMENT NOT NULL, id_parametre INT UNSIGNED DEFAULT NULL, code_valeur VARCHAR(32) NOT NULL, valeur LONGTEXT NOT NULL, valeur2 INT DEFAULT NULL, description_valeur LONGTEXT DEFAULT NULL, INDEX IDX_41E5D02A2CF22C66 (id_parametre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personne (id INT UNSIGNED AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE DEFAULT NULL, cin VARCHAR(12) DEFAULT NULL, date_cin DATE DEFAULT NULL, ville_cin VARCHAR(100) DEFAULT NULL, date_duplicata_cin DATE DEFAULT NULL, ville_duplicata_cin VARCHAR(100) DEFAULT NULL, tel_1 VARCHAR(13) NOT NULL, tel_2 VARCHAR(13) DEFAULT NULL, adresse VARCHAR(180) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, categorie SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT UNSIGNED AUTO_INCREMENT NOT NULL, personne_id INT UNSIGNED DEFAULT NULL, password VARCHAR(128) DEFAULT NULL, last_login DATETIME DEFAULT NULL, is_superuser TINYINT(1) DEFAULT NULL, username VARCHAR(150) NOT NULL, email VARCHAR(254) NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, date_joined DATETIME DEFAULT NULL, avatar LONGBLOB DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3A21BD112 (personne_id), UNIQUE INDEX user_username_key (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_user_auth_permission (auth_user_id INT UNSIGNED NOT NULL, auth_permission_id INT UNSIGNED NOT NULL, INDEX IDX_E5C3D672E94AF366 (auth_user_id), INDEX IDX_E5C3D672F94AD0DA (auth_permission_id), PRIMARY KEY(auth_user_id, auth_permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auth_group_auth_user ADD CONSTRAINT FK_A6A1E121DAAE5EC1 FOREIGN KEY (auth_group_id) REFERENCES auth_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_group_auth_user ADD CONSTRAINT FK_A6A1E121E94AF366 FOREIGN KEY (auth_user_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_group_auth_permission ADD CONSTRAINT FK_79D5E220DAAE5EC1 FOREIGN KEY (auth_group_id) REFERENCES auth_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_group_auth_permission ADD CONSTRAINT FK_79D5E220F94AD0DA FOREIGN KEY (auth_permission_id) REFERENCES auth_permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE parametre_valeur ADD CONSTRAINT FK_41E5D02A2CF22C66 FOREIGN KEY (id_parametre) REFERENCES parametre (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3A21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE auth_user_auth_permission ADD CONSTRAINT FK_E5C3D672E94AF366 FOREIGN KEY (auth_user_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auth_user_auth_permission ADD CONSTRAINT FK_E5C3D672F94AD0DA FOREIGN KEY (auth_permission_id) REFERENCES auth_permission (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auth_group_auth_user DROP FOREIGN KEY FK_A6A1E121DAAE5EC1');
        $this->addSql('ALTER TABLE auth_group_auth_permission DROP FOREIGN KEY FK_79D5E220DAAE5EC1');
        $this->addSql('ALTER TABLE auth_group_auth_permission DROP FOREIGN KEY FK_79D5E220F94AD0DA');
        $this->addSql('ALTER TABLE auth_user_auth_permission DROP FOREIGN KEY FK_E5C3D672F94AD0DA');
        $this->addSql('ALTER TABLE parametre_valeur DROP FOREIGN KEY FK_41E5D02A2CF22C66');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3A21BD112');
        $this->addSql('ALTER TABLE auth_group_auth_user DROP FOREIGN KEY FK_A6A1E121E94AF366');
        $this->addSql('ALTER TABLE auth_user_auth_permission DROP FOREIGN KEY FK_E5C3D672E94AF366');
        $this->addSql('DROP TABLE auth_group');
        $this->addSql('DROP TABLE auth_group_auth_user');
        $this->addSql('DROP TABLE auth_group_auth_permission');
        $this->addSql('DROP TABLE auth_permission');
        $this->addSql('DROP TABLE parametre');
        $this->addSql('DROP TABLE parametre_valeur');
        $this->addSql('DROP TABLE personne');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE auth_user_auth_permission');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
