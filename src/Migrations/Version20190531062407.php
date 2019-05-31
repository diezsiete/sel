<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531062407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reporte_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, convenio_codigo_nombre VARCHAR(255) NOT NULL, pension VARCHAR(140) DEFAULT NULL, salud VARCHAR(140) DEFAULT NULL, banco VARCHAR(140) DEFAULT NULL, cuenta VARCHAR(40) DEFAULT NULL, salario VARCHAR(60) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, devengados_total VARCHAR(25) DEFAULT NULL, deducidos_total VARCHAR(25) DEFAULT NULL, neto VARCHAR(35) DEFAULT NULL, neto_texto VARCHAR(140) DEFAULT NULL, base_salario VARCHAR(140) DEFAULT NULL, base_retencion VARCHAR(140) DEFAULT NULL, met_retencion VARCHAR(140) DEFAULT NULL, porcentaje_retencion VARCHAR(30) DEFAULT NULL, dias_vacaciones_pend VARCHAR(10) DEFAULT NULL, base_pension VARCHAR(140) DEFAULT NULL, INDEX IDX_5A7832B5DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reporte_nomina_detalle (id INT AUTO_INCREMENT NOT NULL, reporte_nomina_id INT NOT NULL, codigo VARCHAR(140) DEFAULT NULL, detalle VARCHAR(140) DEFAULT NULL, cantidad VARCHAR(30) DEFAULT NULL, valor VARCHAR(40) DEFAULT NULL, tipo VARCHAR(8) NOT NULL, INDEX IDX_C049A933167A54EE (reporte_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reporte_nomina ADD CONSTRAINT FK_5A7832B5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('DROP TABLE reporte_nomina');
        $this->addSql('DROP TABLE reporte_nomina_detalle');
    }
}
