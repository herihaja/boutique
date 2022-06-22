<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220618180557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660EC4A74AB');
        $this->addSql('DROP INDEX IDX_4B365660EC4A74AB ON stock');
        $this->addSql('ALTER TABLE stock ADD prix_id INT NOT NULL, DROP unite_id');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660944722F2 FOREIGN KEY (prix_id) REFERENCES prix (id)');
        $this->addSql('CREATE INDEX IDX_4B365660944722F2 ON stock (prix_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660944722F2');
        $this->addSql('DROP INDEX IDX_4B365660944722F2 ON stock');
        $this->addSql('ALTER TABLE stock ADD unite_id INT UNSIGNED NOT NULL, DROP prix_id');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660EC4A74AB FOREIGN KEY (unite_id) REFERENCES parametre_valeur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4B365660EC4A74AB ON stock (unite_id)');
    }
}
