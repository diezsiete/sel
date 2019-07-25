<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190724030052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE autoliquidacion_empleado ADD code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF52F9D43F2A');
        $this->addSql('DROP INDEX IDX_D9D9BF52F9D43F2A ON empleado');
        $this->addSql('ALTER TABLE empleado CHANGE convenio_id convenio_codigo VARCHAR(45) DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF5232717B6D FOREIGN KEY (convenio_codigo) REFERENCES convenio (codigo)');
        $this->addSql('CREATE INDEX IDX_D9D9BF5232717B6D ON empleado (convenio_codigo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE autoliquidacion_empleado DROP code');
        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF5232717B6D');
        $this->addSql('DROP INDEX IDX_D9D9BF5232717B6D ON empleado');
        $this->addSql('ALTER TABLE empleado CHANGE convenio_codigo convenio_id VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF52F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('CREATE INDEX IDX_D9D9BF52F9D43F2A ON empleado (convenio_id)');
    }
}
