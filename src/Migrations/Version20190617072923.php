<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190617072923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE area (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cargo (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ciudad (id INT AUTO_INCREMENT NOT NULL, dpto_id INT NOT NULL, pais_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, n_id VARCHAR(7) DEFAULT NULL, n_pais_id VARCHAR(7) DEFAULT NULL, n_dpto_id VARCHAR(7) DEFAULT NULL, INDEX IDX_8E86059E1FA00731 (dpto_id), INDEX IDX_8E86059EC604D5C6 (pais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE convenio (codigo VARCHAR(45) NOT NULL, codigo_cliente VARCHAR(45) NOT NULL, nombre VARCHAR(105) NOT NULL, direccion VARCHAR(145) NOT NULL, PRIMARY KEY(codigo)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dpto (id INT AUTO_INCREMENT NOT NULL, pais_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, n_id VARCHAR(7) DEFAULT NULL, n_pais_id VARCHAR(7) DEFAULT NULL, INDEX IDX_57AF1723C604D5C6 (pais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empleado (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) DEFAULT NULL, usuario_id INT NOT NULL, sexo VARCHAR(2) NOT NULL, estado_civil VARCHAR(20) NOT NULL, hijos SMALLINT NOT NULL, nacimiento DATE DEFAULT NULL, telefono1 BIGINT DEFAULT NULL, telefono2 BIGINT DEFAULT NULL, direccion VARCHAR(60) DEFAULT NULL, centro_costo VARCHAR(75) NOT NULL, fecha_ingreso DATETIME NOT NULL, fecha_retiro DATETIME DEFAULT NULL, cargo VARCHAR(65) DEFAULT NULL, INDEX IDX_D9D9BF52F9D43F2A (convenio_id), UNIQUE INDEX UNIQ_D9D9BF52DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudio (id INT AUTO_INCREMENT NOT NULL, codigo_id VARCHAR(7) NOT NULL, instituto_id VARCHAR(7) NOT NULL, hv_id INT NOT NULL, nombre VARCHAR(75) NOT NULL, fin DATE DEFAULT NULL, instituto_nombre_alt VARCHAR(75) DEFAULT NULL, ano_estudio INT DEFAULT NULL, horas_estudio INT DEFAULT NULL, graduado TINYINT(1) DEFAULT NULL, semestres_aprobados SMALLINT DEFAULT NULL, cancelo TINYINT(1) DEFAULT NULL, numero_tarjeta VARCHAR(15) DEFAULT NULL, INDEX IDX_BF0B1A29B797D96 (codigo_id), INDEX IDX_BF0B1A296C6EF28 (instituto_id), INDEX IDX_BF0B1A29B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudio_codigo (id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudio_instituto (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiencia (id INT AUTO_INCREMENT NOT NULL, area_id VARCHAR(7) NOT NULL, hv_id INT NOT NULL, empresa VARCHAR(55) NOT NULL, cargo VARCHAR(45) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, duracion SMALLINT NOT NULL, logros_obtenidos LONGTEXT DEFAULT NULL, motivo_retiro LONGTEXT DEFAULT NULL, jefe_inmediato VARCHAR(100) DEFAULT NULL, salario_basico VARCHAR(20) DEFAULT NULL, telefono_jefe VARCHAR(20) DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, fecha_retiro DATE DEFAULT NULL, INDEX IDX_DD0E3034BD0F409C (area_id), INDEX IDX_DD0E3034B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE familiar (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, primer_apellido VARCHAR(15) NOT NULL, segundo_apellido VARCHAR(15) NOT NULL, nombre VARCHAR(30) NOT NULL, nacimiento DATE DEFAULT NULL, parentesco VARCHAR(2) NOT NULL, ocupacion SMALLINT NOT NULL, genero SMALLINT DEFAULT NULL, estado_civil SMALLINT DEFAULT NULL, identificacion VARCHAR(35) DEFAULT NULL, identificacion_tipo VARCHAR(2) DEFAULT NULL, nivel_academico VARCHAR(3) DEFAULT NULL, INDEX IDX_8A34CA5EB83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hv (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, nac_pais_id INT NOT NULL, nac_dpto_id INT DEFAULT NULL, nac_ciudad_id INT DEFAULT NULL, ident_pais_id INT NOT NULL, ident_dpto_id INT DEFAULT NULL, ident_ciudad_id INT DEFAULT NULL, resi_pais_id INT NOT NULL, resi_dpto_id INT DEFAULT NULL, resi_ciudad_id INT DEFAULT NULL, genero SMALLINT NOT NULL, estado_civil SMALLINT NOT NULL, barrio VARCHAR(45) DEFAULT NULL, direccion VARCHAR(60) DEFAULT NULL, grupo_sanguineo VARCHAR(2) DEFAULT NULL, factor_rh VARCHAR(1) DEFAULT NULL, nacionalidad SMALLINT DEFAULT NULL, email_alt VARCHAR(100) DEFAULT NULL, aspiracion_sueldo BIGINT DEFAULT NULL, estatura DOUBLE PRECISION DEFAULT NULL, peso DOUBLE PRECISION DEFAULT NULL, personas_cargo SMALLINT DEFAULT NULL, identificacion_tipo VARCHAR(2) NOT NULL, nacimiento DATE DEFAULT NULL, lmilitar_clase SMALLINT DEFAULT NULL, lmilitar_numero BIGINT DEFAULT NULL, lmilitar_distrito INT DEFAULT NULL, presupuesto_mensual BIGINT DEFAULT NULL, deudas TINYINT(1) DEFAULT NULL, deudas_concepto VARCHAR(255) DEFAULT NULL, nivel_academico VARCHAR(3) NOT NULL, telefono VARCHAR(17) DEFAULT NULL, celular VARCHAR(17) DEFAULT NULL, UNIQUE INDEX UNIQ_559B2759DB38439E (usuario_id), INDEX IDX_559B2759E580352F (nac_pais_id), INDEX IDX_559B27593C24E7D8 (nac_dpto_id), INDEX IDX_559B2759C7D60B4D (nac_ciudad_id), INDEX IDX_559B275939CAB6F1 (ident_pais_id), INDEX IDX_559B2759E06E6406 (ident_dpto_id), INDEX IDX_559B2759ACDCED0C (ident_ciudad_id), INDEX IDX_559B2759A8F701C8 (resi_pais_id), INDEX IDX_559B27597153D33F (resi_dpto_id), INDEX IDX_559B2759F75DD7A4 (resi_ciudad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hv_adjunto (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CBD56489B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idioma (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, idioma_codigo VARCHAR(3) NOT NULL, destreza VARCHAR(2) NOT NULL, INDEX IDX_1DC85E0CB83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licencia_conduccion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pais (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, n_id VARCHAR(7) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profesion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE red_social (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, tipo SMALLINT NOT NULL, cuenta VARCHAR(145) NOT NULL, INDEX IDX_465D8E03B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referencia (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, tipo SMALLINT NOT NULL, nombre VARCHAR(105) NOT NULL, ocupacion VARCHAR(145) NOT NULL, parentesco VARCHAR(45) DEFAULT NULL, celular VARCHAR(20) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, direccion VARCHAR(50) DEFAULT NULL, entidad VARCHAR(100) DEFAULT NULL, INDEX IDX_C01213D8B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reporte_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, convenio_codigo_nombre VARCHAR(255) NOT NULL, pension VARCHAR(140) DEFAULT NULL, salud VARCHAR(140) DEFAULT NULL, banco VARCHAR(140) DEFAULT NULL, cuenta VARCHAR(40) DEFAULT NULL, salario VARCHAR(60) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, devengados_total VARCHAR(25) DEFAULT NULL, deducidos_total VARCHAR(25) DEFAULT NULL, neto VARCHAR(35) DEFAULT NULL, neto_texto VARCHAR(140) DEFAULT NULL, base_salario VARCHAR(140) DEFAULT NULL, base_retencion VARCHAR(140) DEFAULT NULL, met_retencion VARCHAR(140) DEFAULT NULL, porcentaje_retencion VARCHAR(30) DEFAULT NULL, dias_vacaciones_pend VARCHAR(10) DEFAULT NULL, base_pension VARCHAR(140) DEFAULT NULL, INDEX IDX_5A7832B5DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reporte_nomina_detalle (id INT AUTO_INCREMENT NOT NULL, reporte_nomina_id INT NOT NULL, codigo VARCHAR(140) DEFAULT NULL, detalle VARCHAR(140) DEFAULT NULL, cantidad VARCHAR(30) DEFAULT NULL, valor VARCHAR(40) DEFAULT NULL, tipo VARCHAR(8) NOT NULL, INDEX IDX_C049A933167A54EE (reporte_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, identificacion VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(140) DEFAULT NULL, primer_nombre VARCHAR(60) NOT NULL, segundo_nombre VARCHAR(60) DEFAULT NULL, primer_apellido VARCHAR(60) NOT NULL, segundo_apellido VARCHAR(60) DEFAULT NULL, activo TINYINT(1) NOT NULL, acepto_terminos_en DATETIME NOT NULL, ultimo_login DATETIME DEFAULT NULL, type INT DEFAULT NULL, id_old INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2265B05D84291D2B (identificacion), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, requisitos LONGTEXT NOT NULL, nivel SMALLINT NOT NULL, subnivel SMALLINT NOT NULL, contrato_tipo SMALLINT DEFAULT NULL, intensidad_horaria VARCHAR(8) DEFAULT NULL, vacantes_cantidad SMALLINT NOT NULL, salario_rango SMALLINT NOT NULL, salario_publicar TINYINT(1) NOT NULL, salario_neto VARCHAR(31) DEFAULT NULL, salario_adicion INT DEFAULT NULL, salario_adicion_concepto VARCHAR(60) DEFAULT NULL, nivel_academico_curso TINYINT(1) NOT NULL, experiencia SMALLINT NOT NULL, idioma_destreza VARCHAR(2) DEFAULT NULL, genero SMALLINT DEFAULT NULL, empresa VARCHAR(11) DEFAULT NULL, publicada TINYINT(1) NOT NULL, idioma_codigo VARCHAR(3) DEFAULT NULL, nivel_academico VARCHAR(3) NOT NULL, activa TINYINT(1) NOT NULL, vigencia SMALLINT NOT NULL, archivada TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B8B6B464DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_vacante_area (vacante_id INT NOT NULL, vacante_area_id INT NOT NULL, INDEX IDX_503A2CD68B34DB71 (vacante_id), INDEX IDX_503A2CD6A5F8D7AE (vacante_area_id), PRIMARY KEY(vacante_id, vacante_area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_cargo (vacante_id INT NOT NULL, cargo_id VARCHAR(7) NOT NULL, INDEX IDX_34A0E69B8B34DB71 (vacante_id), INDEX IDX_34A0E69B813AC380 (cargo_id), PRIMARY KEY(vacante_id, cargo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_profesion (vacante_id INT NOT NULL, profesion_id INT NOT NULL, INDEX IDX_FFA6E76B8B34DB71 (vacante_id), INDEX IDX_FFA6E76BC5AF4D0F (profesion_id), PRIMARY KEY(vacante_id, profesion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_licencia_conduccion (vacante_id INT NOT NULL, licencia_conduccion_id INT NOT NULL, INDEX IDX_EE67F3E38B34DB71 (vacante_id), INDEX IDX_EE67F3E3A8764065 (licencia_conduccion_id), PRIMARY KEY(vacante_id, licencia_conduccion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_usuario (vacante_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_3A92276F8B34DB71 (vacante_id), INDEX IDX_3A92276FDB38439E (usuario_id), PRIMARY KEY(vacante_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_ciudad (vacante_id INT NOT NULL, ciudad_id INT NOT NULL, INDEX IDX_CE5640498B34DB71 (vacante_id), INDEX IDX_CE564049E8608214 (ciudad_id), PRIMARY KEY(vacante_id, ciudad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_area (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_red_social (id INT AUTO_INCREMENT NOT NULL, vacante_id INT NOT NULL, nombre VARCHAR(20) NOT NULL, id_post VARCHAR(255) DEFAULT NULL, INDEX IDX_7C6BC4878B34DB71 (vacante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vivienda (id INT AUTO_INCREMENT NOT NULL, pais_id INT NOT NULL, dpto_id INT DEFAULT NULL, ciudad_id INT DEFAULT NULL, hv_id INT NOT NULL, direccion VARCHAR(40) NOT NULL, estrato SMALLINT DEFAULT NULL, tipo_vivienda SMALLINT DEFAULT NULL, tenedor SMALLINT DEFAULT NULL, vivienda_actual TINYINT(1) DEFAULT NULL, INDEX IDX_2DFFABDEC604D5C6 (pais_id), INDEX IDX_2DFFABDE1FA00731 (dpto_id), INDEX IDX_2DFFABDEE8608214 (ciudad_id), INDEX IDX_2DFFABDEB83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF52F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF52DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A29B797D96 FOREIGN KEY (codigo_id) REFERENCES estudio_codigo (id)');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A296C6EF28 FOREIGN KEY (instituto_id) REFERENCES estudio_instituto (id)');
        $this->addSql('ALTER TABLE estudio ADD CONSTRAINT FK_BF0B1A29B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034BD0F409C FOREIGN KEY (area_id) REFERENCES area (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759E580352F FOREIGN KEY (nac_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27593C24E7D8 FOREIGN KEY (nac_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759C7D60B4D FOREIGN KEY (nac_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B275939CAB6F1 FOREIGN KEY (ident_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759E06E6406 FOREIGN KEY (ident_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759ACDCED0C FOREIGN KEY (ident_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759A8F701C8 FOREIGN KEY (resi_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27597153D33F FOREIGN KEY (resi_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759F75DD7A4 FOREIGN KEY (resi_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv_adjunto ADD CONSTRAINT FK_CBD56489B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE idioma ADD CONSTRAINT FK_1DC85E0CB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE red_social ADD CONSTRAINT FK_465D8E03B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE reporte_nomina ADD CONSTRAINT FK_5A7832B5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE vacante_vacante_area ADD CONSTRAINT FK_503A2CD68B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_vacante_area ADD CONSTRAINT FK_503A2CD6A5F8D7AE FOREIGN KEY (vacante_area_id) REFERENCES vacante_area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76BC5AF4D0F FOREIGN KEY (profesion_id) REFERENCES profesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E38B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E3A8764065 FOREIGN KEY (licencia_conduccion_id) REFERENCES licencia_conduccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276F8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE5640498B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE564049E8608214 FOREIGN KEY (ciudad_id) REFERENCES ciudad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_red_social ADD CONSTRAINT FK_7C6BC4878B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDE1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEE8608214 FOREIGN KEY (ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034BD0F409C');
        $this->addSql('ALTER TABLE vacante_cargo DROP FOREIGN KEY FK_34A0E69B813AC380');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759C7D60B4D');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759ACDCED0C');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759F75DD7A4');
        $this->addSql('ALTER TABLE vacante_ciudad DROP FOREIGN KEY FK_CE564049E8608214');
        $this->addSql('ALTER TABLE vivienda DROP FOREIGN KEY FK_2DFFABDEE8608214');
        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF52F9D43F2A');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B27593C24E7D8');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759E06E6406');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B27597153D33F');
        $this->addSql('ALTER TABLE vivienda DROP FOREIGN KEY FK_2DFFABDE1FA00731');
        $this->addSql('ALTER TABLE estudio DROP FOREIGN KEY FK_BF0B1A29B797D96');
        $this->addSql('ALTER TABLE estudio DROP FOREIGN KEY FK_BF0B1A296C6EF28');
        $this->addSql('ALTER TABLE estudio DROP FOREIGN KEY FK_BF0B1A29B83428F3');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034B83428F3');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5EB83428F3');
        $this->addSql('ALTER TABLE hv_adjunto DROP FOREIGN KEY FK_CBD56489B83428F3');
        $this->addSql('ALTER TABLE idioma DROP FOREIGN KEY FK_1DC85E0CB83428F3');
        $this->addSql('ALTER TABLE red_social DROP FOREIGN KEY FK_465D8E03B83428F3');
        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8B83428F3');
        $this->addSql('ALTER TABLE vivienda DROP FOREIGN KEY FK_2DFFABDEB83428F3');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion DROP FOREIGN KEY FK_EE67F3E3A8764065');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759E580352F');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B275939CAB6F1');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759A8F701C8');
        $this->addSql('ALTER TABLE vivienda DROP FOREIGN KEY FK_2DFFABDEC604D5C6');
        $this->addSql('ALTER TABLE vacante_profesion DROP FOREIGN KEY FK_FFA6E76BC5AF4D0F');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF52DB38439E');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759DB38439E');
        $this->addSql('ALTER TABLE reporte_nomina DROP FOREIGN KEY FK_5A7832B5DB38439E');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DB38439E');
        $this->addSql('ALTER TABLE vacante_usuario DROP FOREIGN KEY FK_3A92276FDB38439E');
        $this->addSql('ALTER TABLE vacante_vacante_area DROP FOREIGN KEY FK_503A2CD68B34DB71');
        $this->addSql('ALTER TABLE vacante_cargo DROP FOREIGN KEY FK_34A0E69B8B34DB71');
        $this->addSql('ALTER TABLE vacante_profesion DROP FOREIGN KEY FK_FFA6E76B8B34DB71');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion DROP FOREIGN KEY FK_EE67F3E38B34DB71');
        $this->addSql('ALTER TABLE vacante_usuario DROP FOREIGN KEY FK_3A92276F8B34DB71');
        $this->addSql('ALTER TABLE vacante_ciudad DROP FOREIGN KEY FK_CE5640498B34DB71');
        $this->addSql('ALTER TABLE vacante_red_social DROP FOREIGN KEY FK_7C6BC4878B34DB71');
        $this->addSql('ALTER TABLE vacante_vacante_area DROP FOREIGN KEY FK_503A2CD6A5F8D7AE');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE ciudad');
        $this->addSql('DROP TABLE convenio');
        $this->addSql('DROP TABLE dpto');
        $this->addSql('DROP TABLE empleado');
        $this->addSql('DROP TABLE estudio');
        $this->addSql('DROP TABLE estudio_codigo');
        $this->addSql('DROP TABLE estudio_instituto');
        $this->addSql('DROP TABLE experiencia');
        $this->addSql('DROP TABLE familiar');
        $this->addSql('DROP TABLE hv');
        $this->addSql('DROP TABLE hv_adjunto');
        $this->addSql('DROP TABLE idioma');
        $this->addSql('DROP TABLE licencia_conduccion');
        $this->addSql('DROP TABLE pais');
        $this->addSql('DROP TABLE profesion');
        $this->addSql('DROP TABLE red_social');
        $this->addSql('DROP TABLE referencia');
        $this->addSql('DROP TABLE reporte_nomina');
        $this->addSql('DROP TABLE reporte_nomina_detalle');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE vacante');
        $this->addSql('DROP TABLE vacante_vacante_area');
        $this->addSql('DROP TABLE vacante_cargo');
        $this->addSql('DROP TABLE vacante_profesion');
        $this->addSql('DROP TABLE vacante_licencia_conduccion');
        $this->addSql('DROP TABLE vacante_usuario');
        $this->addSql('DROP TABLE vacante_ciudad');
        $this->addSql('DROP TABLE vacante_area');
        $this->addSql('DROP TABLE vacante_red_social');
        $this->addSql('DROP TABLE vivienda');
    }
}
