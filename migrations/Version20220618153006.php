<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618153006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_item DROP FOREIGN KEY FK_8221ABC9FE95D117');
        $this->addSql('CREATE TABLE mouvement (id INT AUTO_INCREMENT NOT NULL, caissier_id INT UNSIGNED NOT NULL, mode_paiement_id INT UNSIGNED DEFAULT NULL, date_mouvement DATETIME NOT NULL, montant_total BIGINT NOT NULL, is_vente TINYINT(1) NOT NULL, montant_remis BIGINT NOT NULL, montant_rendu BIGINT DEFAULT NULL, INDEX IDX_5B51FC3EB514973B (caissier_id), INDEX IDX_5B51FC3E438F5B63 (mode_paiement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement_item (id INT AUTO_INCREMENT NOT NULL, produit_id INT UNSIGNED NOT NULL, mouvement_id INT NOT NULL, prix_ut_id INT NOT NULL, nombre INT NOT NULL, total INT NOT NULL, INDEX IDX_A4AE3AE5F347EFB (produit_id), INDEX IDX_A4AE3AE5ECD1C222 (mouvement_id), INDEX IDX_A4AE3AE56A02DD15 (prix_ut_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3EB514973B FOREIGN KEY (caissier_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3E438F5B63 FOREIGN KEY (mode_paiement_id) REFERENCES parametre_valeur (id)');
        $this->addSql('ALTER TABLE mouvement_item ADD CONSTRAINT FK_A4AE3AE5F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE mouvement_item ADD CONSTRAINT FK_A4AE3AE5ECD1C222 FOREIGN KEY (mouvement_id) REFERENCES mouvement (id)');
        $this->addSql('ALTER TABLE mouvement_item ADD CONSTRAINT FK_A4AE3AE56A02DD15 FOREIGN KEY (prix_ut_id) REFERENCES prix (id)');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE achat_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_item DROP FOREIGN KEY FK_A4AE3AE5ECD1C222');
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, caissier_id INT UNSIGNED NOT NULL, mode_paiement_id INT UNSIGNED DEFAULT NULL, date_achat DATETIME NOT NULL, montant_total BIGINT NOT NULL, montant_remis BIGINT NOT NULL, montant_rendu BIGINT DEFAULT NULL, INDEX IDX_26A98456438F5B63 (mode_paiement_id), INDEX IDX_26A98456B514973B (caissier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE achat_item (id INT AUTO_INCREMENT NOT NULL, produit_id INT UNSIGNED NOT NULL, achat_id INT NOT NULL, prix_ut_id INT NOT NULL, nombre INT NOT NULL, total INT NOT NULL, INDEX IDX_8221ABC96A02DD15 (prix_ut_id), INDEX IDX_8221ABC9F347EFB (produit_id), INDEX IDX_8221ABC9FE95D117 (achat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456438F5B63 FOREIGN KEY (mode_paiement_id) REFERENCES parametre_valeur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456B514973B FOREIGN KEY (caissier_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_item ADD CONSTRAINT FK_8221ABC96A02DD15 FOREIGN KEY (prix_ut_id) REFERENCES prix (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_item ADD CONSTRAINT FK_8221ABC9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE achat_item ADD CONSTRAINT FK_8221ABC9FE95D117 FOREIGN KEY (achat_id) REFERENCES achat (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE mouvement');
        $this->addSql('DROP TABLE mouvement_item');
    }
}
