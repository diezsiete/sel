<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603083251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE estudio (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, codigo_id VARCHAR(7) NOT NULL, instituto_id VARCHAR(7) NOT NULL, nombre VARCHAR(75) NOT NULL, fin DATE DEFAULT NULL, instituto_nombre_alt VARCHAR(75) DEFAULT NULL, ano_estudio INT DEFAULT NULL, horas_estudio INT DEFAULT NULL, graduado TINYINT(1) DEFAULT NULL, semestres_aprobados SMALLINT DEFAULT NULL, cancelo TINYINT(1) DEFAULT NULL, numero_tarjeta VARCHAR(15) DEFAULT NULL, INDEX IDX_BF0B1A29B83428F3 (hv_id), INDEX IDX_BF0B1A29B797D96 (codigo_id), INDEX IDX_BF0B1A296C6EF28 (instituto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudio_codigo (id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudio_instituto (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiencia (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, area_id VARCHAR(7) NOT NULL, empresa VARCHAR(55) NOT NULL, cargo VARCHAR(45) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, duracion SMALLINT NOT NULL, logros_obtenidos LONGTEXT DEFAULT NULL, motivo_retiro LONGTEXT DEFAULT NULL, jefe_inmediato VARCHAR(100) DEFAULT NULL, salario_basico INT DEFAULT NULL, telefono_jefe INT DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, fecha_retiro DATE DEFAULT NULL, INDEX IDX_DD0E3034B83428F3 (hv_id), INDEX IDX_DD0E3034BD0F409C (area_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE familiar (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, nivel_academico_id VARCHAR(7) DEFAULT NULL, primer_apellido VARCHAR(15) NOT NULL, segundo_apellido VARCHAR(15) NOT NULL, nombre VARCHAR(30) NOT NULL, nacimiento DATE DEFAULT NULL, parentesco VARCHAR(2) NOT NULL, ocupacion SMALLINT NOT NULL, genero SMALLINT DEFAULT NULL, estado_civil SMALLINT DEFAULT NULL, identificacion VARCHAR(35) DEFAULT NULL, identificacion_tipo VARCHAR(2) DEFAULT NULL, INDEX IDX_8A34CA5EB83428F3 (hv_id), INDEX IDX_8A34CA5EC21F5FA8 (nivel_academico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A29B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A29B797D96 FOREIGN KEY (codigo_id) REFERENCES estudio_codigo (id)');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A296C6EF28 FOREIGN KEY (instituto_id) REFERENCES estudio_instituto (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EC21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id)');
        $this->addSql('ALTER TABLE hv ADD identificacion_tipo VARCHAR(2) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE estudio DROP FOREIGN KEY FK_BF0B1A29B797D96');
        $this->addSql('ALTER TABLE estudio DROP FOREIGN KEY FK_BF0B1A296C6EF28');
        $this->addSql('DROP TABLE estudio');
        $this->addSql('DROP TABLE estudio_codigo');
        $this->addSql('DROP TABLE estudio_instituto');
        $this->addSql('DROP TABLE experiencia');
        $this->addSql('DROP TABLE familiar');
        $this->addSql('ALTER TABLE hv DROP identificacion_tipo');
    }
}
