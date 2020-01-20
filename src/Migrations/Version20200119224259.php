<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200119224259 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE novasoft_listado_nomina (id INT AUTO_INCREMENT NOT NULL, convenio_codigo VARCHAR(45) NOT NULL, tipo_liquidacion VARCHAR(2) NOT NULL, fecha_nomina DATE NOT NULL, INDEX IDX_EA1F007432717B6D (convenio_codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_listado_nomina_empleado (id INT AUTO_INCREMENT NOT NULL, empleado_id INT NOT NULL, listado_nomina_id INT NOT NULL, identificacion VARCHAR(40) NOT NULL, sucursal_codigo VARCHAR(3) NOT NULL, sucursal_nombre VARCHAR(100) NOT NULL, contrato INT NOT NULL, nombre_cargo VARCHAR(250) NOT NULL, fecha_ingreso DATE NOT NULL, sueldo INT NOT NULL, riesgo_cargo DOUBLE PRECISION DEFAULT NULL, INDEX IDX_7C1FBC28952BE730 (empleado_id), INDEX IDX_7C1FBC2838A0F459 (listado_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_listado_nomina_grupo (id INT AUTO_INCREMENT NOT NULL, listado_nomina_id INT NOT NULL, nombre VARCHAR(30) NOT NULL, valor_total INT NOT NULL, INDEX IDX_3A8A2A2738A0F459 (listado_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_listado_nomina_grupo_total (id INT AUTO_INCREMENT NOT NULL, grupo_id INT NOT NULL, empleado_id INT DEFAULT NULL, valor INT NOT NULL, identificacion VARCHAR(40) NOT NULL, INDEX IDX_72D739F09C833003 (grupo_id), INDEX IDX_72D739F0952BE730 (empleado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_listado_nomina_renglon (id INT AUTO_INCREMENT NOT NULL, subgrupo_id INT NOT NULL, empleado_id INT NOT NULL, cantidad DOUBLE PRECISION NOT NULL, valor INT NOT NULL, INDEX IDX_FB459E5797138D8A (subgrupo_id), INDEX IDX_FB459E57952BE730 (empleado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_listado_nomina_subgrupo (id INT AUTO_INCREMENT NOT NULL, grupo_id INT NOT NULL, cantidad_total DOUBLE PRECISION NOT NULL, valor_total INT NOT NULL, nombre VARCHAR(200) NOT NULL, codigo VARCHAR(6) DEFAULT NULL, INDEX IDX_537B77739C833003 (grupo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE novasoft_listado_nomina ADD CONSTRAINT FK_EA1F007432717B6D FOREIGN KEY (convenio_codigo) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_empleado ADD CONSTRAINT FK_7C1FBC28952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_empleado ADD CONSTRAINT FK_7C1FBC2838A0F459 FOREIGN KEY (listado_nomina_id) REFERENCES novasoft_listado_nomina (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo ADD CONSTRAINT FK_3A8A2A2738A0F459 FOREIGN KEY (listado_nomina_id) REFERENCES novasoft_listado_nomina (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo_total ADD CONSTRAINT FK_72D739F09C833003 FOREIGN KEY (grupo_id) REFERENCES novasoft_listado_nomina_grupo (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo_total ADD CONSTRAINT FK_72D739F0952BE730 FOREIGN KEY (empleado_id) REFERENCES novasoft_listado_nomina_empleado (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_renglon ADD CONSTRAINT FK_FB459E5797138D8A FOREIGN KEY (subgrupo_id) REFERENCES novasoft_listado_nomina_subgrupo (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_renglon ADD CONSTRAINT FK_FB459E57952BE730 FOREIGN KEY (empleado_id) REFERENCES novasoft_listado_nomina_empleado (id)');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_subgrupo ADD CONSTRAINT FK_537B77739C833003 FOREIGN KEY (grupo_id) REFERENCES novasoft_listado_nomina_grupo (id)');
        $this->addSql('ALTER TABLE empleado CHANGE cargo cargo VARCHAR(250) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE novasoft_listado_nomina_empleado DROP FOREIGN KEY FK_7C1FBC2838A0F459');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo DROP FOREIGN KEY FK_3A8A2A2738A0F459');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo_total DROP FOREIGN KEY FK_72D739F0952BE730');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_renglon DROP FOREIGN KEY FK_FB459E57952BE730');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_grupo_total DROP FOREIGN KEY FK_72D739F09C833003');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_subgrupo DROP FOREIGN KEY FK_537B77739C833003');
        $this->addSql('ALTER TABLE novasoft_listado_nomina_renglon DROP FOREIGN KEY FK_FB459E5797138D8A');
        $this->addSql('DROP TABLE novasoft_listado_nomina');
        $this->addSql('DROP TABLE novasoft_listado_nomina_empleado');
        $this->addSql('DROP TABLE novasoft_listado_nomina_grupo');
        $this->addSql('DROP TABLE novasoft_listado_nomina_grupo_total');
        $this->addSql('DROP TABLE novasoft_listado_nomina_renglon');
        $this->addSql('DROP TABLE novasoft_listado_nomina_subgrupo');
        $this->addSql('ALTER TABLE empleado CHANGE cargo cargo VARCHAR(65) DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}
