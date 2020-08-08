<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200808023206 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv_adjunto DROP FOREIGN KEY FK_CBD56489B83428F3');
        $this->addSql('ALTER TABLE hv_adjunto CHANGE hv_id hv_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hv_adjunto ADD CONSTRAINT FK_CBD56489B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv_adjunto DROP FOREIGN KEY FK_CBD56489B83428F3');
        $this->addSql('ALTER TABLE hv_adjunto CHANGE hv_id hv_id INT NOT NULL');
        $this->addSql('ALTER TABLE hv_adjunto ADD CONSTRAINT FK_CBD56489B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id) ON DELETE CASCADE');
    }
}
