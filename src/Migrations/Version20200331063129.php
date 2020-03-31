<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200331063129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE napi_certificado_ingresos (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, identificacion VARCHAR(15) NOT NULL, fecha_inicial DATE DEFAULT NULL, fecha_final DATE NOT NULL, numero_formulario INT NOT NULL, nit VARCHAR(12) NOT NULL, dv VARCHAR(1) NOT NULL, razon_social VARCHAR(200) NOT NULL, tipo_documento VARCHAR(2) NOT NULL, primer_apellido VARCHAR(50) NOT NULL, segundo_apellido VARCHAR(50) NOT NULL, primer_nombre VARCHAR(50) NOT NULL, segundo_nombre VARCHAR(50) NOT NULL, fecha_expedicion DATETIME NOT NULL, ingreso_salario INT NOT NULL, ciudad VARCHAR(30) NOT NULL, ciudad_codigo VARCHAR(5) NOT NULL, ingreso_honorarios INT NOT NULL, ingreso_servicios INT NOT NULL, ingreso_comisiones INT NOT NULL, ingreso_prestaciones INT NOT NULL, ingreso_viaticos INT NOT NULL, ingreso_representacion INT NOT NULL, ingreso_compensaciones INT NOT NULL, ingreso_otros INT NOT NULL, ingreso_cesantias INT NOT NULL, ingreso_pensiones INT NOT NULL, aportes_salud INT NOT NULL, aportes_obligatorios_pensiones INT NOT NULL, aportes_voluntarios_pensiones INT NOT NULL, aportes_afc INT NOT NULL, valor_retencion INT NOT NULL, INDEX IDX_D3360FE3DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE napi_certificado_ingresos ADD CONSTRAINT FK_D3360FE3DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE napi_certificado_ingresos');
    }
}
