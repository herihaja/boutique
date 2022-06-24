<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220624193133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE unite_relation (id INT AUTO_INCREMENT NOT NULL, produit_id INT UNSIGNED NOT NULL, unite1_id INT UNSIGNED NOT NULL, unite2_id INT UNSIGNED NOT NULL, multiple DOUBLE PRECISION NOT NULL, INDEX IDX_2DECDC5AF347EFB (produit_id), INDEX IDX_2DECDC5A97841D87 (unite1_id), INDEX IDX_2DECDC5A8531B269 (unite2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE unite_relation ADD CONSTRAINT FK_2DECDC5AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE unite_relation ADD CONSTRAINT FK_2DECDC5A97841D87 FOREIGN KEY (unite1_id) REFERENCES parametre_valeur (id)');
        $this->addSql('ALTER TABLE unite_relation ADD CONSTRAINT FK_2DECDC5A8531B269 FOREIGN KEY (unite2_id) REFERENCES parametre_valeur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE unite_relation');
    }
}
