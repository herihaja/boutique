<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220604185034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produit_unites (produit_id INT UNSIGNED NOT NULL, parametre_valeur_id INT UNSIGNED NOT NULL, INDEX IDX_FF2DF8A6F347EFB (produit_id), INDEX IDX_FF2DF8A62C3D5AF (parametre_valeur_id), PRIMARY KEY(produit_id, parametre_valeur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit_unites ADD CONSTRAINT FK_FF2DF8A6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_unites ADD CONSTRAINT FK_FF2DF8A62C3D5AF FOREIGN KEY (parametre_valeur_id) REFERENCES parametre_valeur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE produit_unites');
    }
}
