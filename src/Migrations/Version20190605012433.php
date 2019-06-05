<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605012433 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad ADD n_id VARCHAR(7) NOT NULL, ADD n_pais_id VARCHAR(7) NOT NULL, ADD n_dpto_id VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE dpto ADD n_id VARCHAR(7) NOT NULL, ADD n_pais_id VARCHAR(7) NOT NULL');
        $this->addSql('ALTER TABLE pais ADD n_id VARCHAR(7) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad DROP n_id, DROP n_pais_id, DROP n_dpto_id');
        $this->addSql('ALTER TABLE dpto DROP n_id, DROP n_pais_id');
        $this->addSql('ALTER TABLE pais DROP n_id');
    }
}
