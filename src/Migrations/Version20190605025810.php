<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605025810 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv ADD lmilitar_clase SMALLINT DEFAULT NULL, ADD lmilitar_numero BIGINT DEFAULT NULL, ADD lmilitar_distrito INT DEFAULT NULL, ADD presupuesto_mensual BIGINT DEFAULT NULL, ADD deudas TINYINT(1) DEFAULT NULL, ADD deudas_concepto VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv DROP lmilitar_clase, DROP lmilitar_numero, DROP lmilitar_distrito, DROP presupuesto_mensual, DROP deudas, DROP deudas_concepto');
    }
}
