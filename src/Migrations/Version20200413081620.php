<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200413081620 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE napi_se_certificado_laboral (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, empleado VARCHAR(12) NOT NULL, numero_identificacion VARCHAR(15) DEFAULT NULL, primer_apellido VARCHAR(50) DEFAULT NULL, segundo_apellido VARCHAR(50) DEFAULT NULL, nombre VARCHAR(100) DEFAULT NULL, pais_expedicion VARCHAR(3) DEFAULT NULL, ciudad_expedicion VARCHAR(3) NOT NULL, nombre_ciudad VARCHAR(30) DEFAULT NULL, fecha_ingreso_texto VARCHAR(25) DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, fecha_retiro DATE DEFAULT NULL, tipo_contrato VARCHAR(2) DEFAULT NULL, nombre_contrato VARCHAR(100) DEFAULT NULL, cla_sal SMALLINT DEFAULT NULL, descripcion VARCHAR(10) DEFAULT NULL, salario INT DEFAULT NULL, salario_texto VARCHAR(255) DEFAULT NULL, codigo_cargo VARCHAR(8) NOT NULL, nombre_cargo VARCHAR(250) DEFAULT NULL, fecha_certificado VARCHAR(25) DEFAULT NULL, tipo_id VARCHAR(40) DEFAULT NULL, sexo INT NOT NULL, empresa_usuaria VARCHAR(40) DEFAULT NULL, activo TINYINT(1) NOT NULL, INDEX IDX_295B20B3DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE napi_se_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, identificacion VARCHAR(15) NOT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, nombre_convenio VARCHAR(100) NOT NULL, INDEX IDX_E0F1DA88DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE napi_se_certificado_laboral ADD CONSTRAINT FK_295B20B3DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE napi_se_nomina ADD CONSTRAINT FK_E0F1DA88DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE napi_se_certificado_laboral');
        $this->addSql('DROP TABLE napi_se_nomina');
    }
}
