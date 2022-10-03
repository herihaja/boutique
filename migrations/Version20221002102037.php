<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221002102037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_item ADD date_peremption DATE NOT NULL default \'1900-01-01)\'');
        $this->addSql('ALTER TABLE stock ADD date_peremption DATE NOT NULL default \'1900-01-01)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement_item DROP date_peremption');
        $this->addSql('ALTER TABLE stock DROP date_peremption');
    }
}
