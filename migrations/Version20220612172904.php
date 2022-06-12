<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220612172904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_item ADD prix_ut_id INT NOT NULL, ADD total INT NOT NULL');
        $this->addSql('ALTER TABLE achat_item ADD CONSTRAINT FK_8221ABC96A02DD15 FOREIGN KEY (prix_ut_id) REFERENCES prix (id)');
        $this->addSql('CREATE INDEX IDX_8221ABC96A02DD15 ON achat_item (prix_ut_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat_item DROP FOREIGN KEY FK_8221ABC96A02DD15');
        $this->addSql('DROP INDEX IDX_8221ABC96A02DD15 ON achat_item');
        $this->addSql('ALTER TABLE achat_item DROP prix_ut_id, DROP total');
    }
}
