<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190525055248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE convenio (codigo VARCHAR(45) NOT NULL, codigo_cliente VARCHAR(45) NOT NULL, nombre VARCHAR(105) NOT NULL, direccion VARCHAR(145) NOT NULL, PRIMARY KEY(codigo)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empleado (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) DEFAULT NULL, identificacion BIGINT NOT NULL, nombre VARCHAR(255) NOT NULL, sexo VARCHAR(2) NOT NULL, estado_civil VARCHAR(20) NOT NULL, hijos SMALLINT NOT NULL, nacimiento DATE DEFAULT NULL, telefono1 BIGINT DEFAULT NULL, telefono2 BIGINT DEFAULT NULL, direccion VARCHAR(60) DEFAULT NULL, email VARCHAR(65) DEFAULT NULL, centro_costo VARCHAR(75) NOT NULL, fecha_ingreso DATETIME NOT NULL, fecha_retiro DATETIME DEFAULT NULL, cargo VARCHAR(65) DEFAULT NULL, INDEX IDX_D9D9BF52F9D43F2A (convenio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF52F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF52F9D43F2A');
        $this->addSql('DROP TABLE convenio');
        $this->addSql('DROP TABLE empleado');
    }
}
