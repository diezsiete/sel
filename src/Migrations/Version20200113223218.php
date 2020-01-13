<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200113223218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trabajador_activo DROP FOREIGN KEY FK_EA8EC4CD2CC5C277');
        $this->addSql('ALTER TABLE liquidacion_nomina_renglon DROP FOREIGN KEY FK_854B628C490FD2A7');
        $this->addSql('ALTER TABLE liquidacion_nomina_total DROP FOREIGN KEY FK_24AC4B99490FD2A7');
        $this->addSql('ALTER TABLE liquidacion_nomina DROP FOREIGN KEY FK_8466F7AC3C0CE86C');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_renglon DROP FOREIGN KEY FK_823B9CD7AEB8D475');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_total DROP FOREIGN KEY FK_A9FE2B89AEB8D475');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('CREATE TABLE novasoft_centro_costo (codigo VARCHAR(20) NOT NULL, nombre VARCHAR(70) NOT NULL, PRIMARY KEY(codigo)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_certificado_ingresos (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, dv SMALLINT NOT NULL, primer_apellido VARCHAR(60) NOT NULL, segundo_apellido VARCHAR(60) DEFAULT NULL, nombres VARCHAR(60) NOT NULL, periodo_certificacion_de DATE NOT NULL, periodo_certificacion_a DATE NOT NULL, fecha_expedicion DATE NOT NULL, lugar_retencion VARCHAR(60) DEFAULT NULL, codigo_departamento VARCHAR(2) NOT NULL, codigo_ciudad VARCHAR(3) NOT NULL, pagos_salarios VARCHAR(60) DEFAULT NULL, pagos_honorarios VARCHAR(60) DEFAULT NULL, pagos_servicios VARCHAR(60) DEFAULT NULL, pagos_comisiones VARCHAR(60) DEFAULT NULL, pagos_prestaciones VARCHAR(60) DEFAULT NULL, pagos_viaticos VARCHAR(60) DEFAULT NULL, pagos_representacion VARCHAR(60) DEFAULT NULL, pagos_compensaciones VARCHAR(60) DEFAULT NULL, pagos_otros VARCHAR(60) DEFAULT NULL, pagos_cesantias VARCHAR(60) DEFAULT NULL, pagos_jubilacion VARCHAR(60) DEFAULT NULL, total_ingresos_brutos VARCHAR(30) DEFAULT NULL, aportes_salud VARCHAR(20) DEFAULT NULL, aportes_pension_obligatorio VARCHAR(20) DEFAULT NULL, aportes_pension_voluntario VARCHAR(20) DEFAULT NULL, aportes_afc VARCHAR(20) DEFAULT NULL, valor_retencion_fuente VARCHAR(20) DEFAULT NULL, pagador_nombre VARCHAR(60) NOT NULL, pagador_nit VARCHAR(30) NOT NULL, total_texto VARCHAR(140) DEFAULT NULL, total VARCHAR(30) DEFAULT NULL, certifico_texto LONGTEXT DEFAULT NULL, INDEX IDX_53CD19EBDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_certificado_laboral (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre VARCHAR(140) NOT NULL, primer_apellido VARCHAR(60) NOT NULL, segundo_apellido VARCHAR(60) DEFAULT NULL, activo TINYINT(1) DEFAULT NULL, cedula VARCHAR(32) NOT NULL, contrato VARCHAR(60) NOT NULL, empresa_usuaria VARCHAR(60) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, nsalario VARCHAR(255) DEFAULT NULL, salario VARCHAR(140) DEFAULT NULL, hombre TINYINT(1) DEFAULT NULL, fecha_ingreso DATE NOT NULL, fecha_egreso DATE DEFAULT NULL, tipo_documento VARCHAR(40) NOT NULL, email VARCHAR(255) DEFAULT NULL, fecha_ingreso_textual VARCHAR(255) DEFAULT NULL, fecha_certificado_textual VARCHAR(255) DEFAULT NULL, INDEX IDX_4089A27BDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_contrato (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, identificacion VARCHAR(30) NOT NULL, nombre_completo VARCHAR(80) NOT NULL, codigo_sucursal VARCHAR(14) NOT NULL, nombre_sucursal VARCHAR(40) NOT NULL, centro_costo VARCHAR(14) NOT NULL, nombre_centro_costo VARCHAR(30) NOT NULL, regimen_cesantias VARCHAR(40) NOT NULL, tipo_contrato VARCHAR(50) NOT NULL, numero_contrato VARCHAR(30) NOT NULL, pension VARCHAR(40) NOT NULL, ultimo_cargo VARCHAR(140) NOT NULL, causa_terminacion_contrato VARCHAR(70) NOT NULL, salud VARCHAR(80) NOT NULL, dias_totales VARCHAR(14) NOT NULL, dias_licencia VARCHAR(6) NOT NULL, ultimo_sueldo VARCHAR(20) NOT NULL, base_cesantias VARCHAR(20) NOT NULL, base_prima VARCHAR(20) NOT NULL, base_vacaciones VARCHAR(20) NOT NULL, INDEX IDX_3286656DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, empleado_id INT NOT NULL, resumen_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, ingreso_basico INT DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, cargo_codigo VARCHAR(15) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, cuenta VARCHAR(50) DEFAULT NULL, INDEX IDX_D98EFD52F9D43F2A (convenio_id), INDEX IDX_D98EFD52952BE730 (empleado_id), INDEX IDX_D98EFD523C0CE86C (resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, concepto_codigo VARCHAR(10) DEFAULT NULL, concepto VARCHAR(140) DEFAULT NULL, unidades DOUBLE PRECISION DEFAULT NULL, base DOUBLE PRECISION NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, centro_costo VARCHAR(70) DEFAULT NULL, INDEX IDX_F14EA92F490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina_resumen (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, INDEX IDX_6803E7B5F9D43F2A (convenio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina_resumen_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, concepto VARCHAR(255) DEFAULT NULL, unidades INT DEFAULT NULL, base INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, INDEX IDX_347C6075AEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina_resumen_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, unidades INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, neto INT DEFAULT NULL, UNIQUE INDEX UNIQ_CC95A1BDAEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_liquidacion_nomina_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, unidades INT NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, neto INT NOT NULL, UNIQUE INDEX UNIQ_362BFF65490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, convenio_codigo_nombre VARCHAR(140) NOT NULL, pension VARCHAR(140) DEFAULT NULL, salud VARCHAR(140) DEFAULT NULL, banco VARCHAR(140) DEFAULT NULL, cuenta VARCHAR(40) DEFAULT NULL, salario VARCHAR(60) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, devengados_total VARCHAR(25) DEFAULT NULL, deducidos_total VARCHAR(25) DEFAULT NULL, neto VARCHAR(35) DEFAULT NULL, neto_texto VARCHAR(140) DEFAULT NULL, base_salario VARCHAR(140) DEFAULT NULL, base_retencion VARCHAR(140) DEFAULT NULL, met_retencion VARCHAR(140) DEFAULT NULL, porcentaje_retencion VARCHAR(30) DEFAULT NULL, dias_vacaciones_pend VARCHAR(10) DEFAULT NULL, base_pension VARCHAR(140) DEFAULT NULL, INDEX IDX_F8FE35A5DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_nomina_detalle (id INT AUTO_INCREMENT NOT NULL, nomina_id INT NOT NULL, codigo VARCHAR(140) DEFAULT NULL, detalle VARCHAR(140) DEFAULT NULL, cantidad VARCHAR(30) DEFAULT NULL, valor VARCHAR(40) DEFAULT NULL, tipo VARCHAR(8) NOT NULL, INDEX IDX_BB5B9DA58261CA50 (nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE novasoft_trabajador_activo (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, empleado_id INT NOT NULL, centro_costo_id VARCHAR(20) DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, ingreso_basico INT DEFAULT NULL, labor VARCHAR(255) DEFAULT NULL, fecha_retiro DATE DEFAULT NULL, porcentaje_riesgo DOUBLE PRECISION DEFAULT NULL, cuenta VARCHAR(30) DEFAULT NULL, caja VARCHAR(30) DEFAULT NULL, promotora_salud VARCHAR(70) DEFAULT NULL, admin_pension VARCHAR(70) DEFAULT NULL, INDEX IDX_6A9F3837F9D43F2A (convenio_id), UNIQUE INDEX UNIQ_6A9F3837952BE730 (empleado_id), INDEX IDX_6A9F38372CC5C277 (centro_costo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_certificado_ingresos (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, periodo DATE NOT NULL, source VARCHAR(8) NOT NULL, INDEX IDX_CEA2E2C9DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_certificado_laboral (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha_ingreso DATE NOT NULL, fecha_retiro DATE DEFAULT NULL, convenio VARCHAR(105) DEFAULT NULL, source VARCHAR(8) NOT NULL, INDEX IDX_FD052FFEDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_liquidacion_contrato (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha_ingreso DATE NOT NULL, fecha_retiro DATE NOT NULL, cargo VARCHAR(60) DEFAULT NULL, contrato VARCHAR(40) DEFAULT NULL, source VARCHAR(8) NOT NULL, INDEX IDX_9E479D74DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATE NOT NULL, convenio VARCHAR(105) NOT NULL, source VARCHAR(8) NOT NULL, source_id VARCHAR(27) NOT NULL, INDEX IDX_4AC9A1FDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE novasoft_certificado_ingresos ADD CONSTRAINT FK_53CD19EBDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE novasoft_certificado_laboral ADD CONSTRAINT FK_4089A27BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_contrato ADD CONSTRAINT FK_3286656DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina ADD CONSTRAINT FK_D98EFD52F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina ADD CONSTRAINT FK_D98EFD52952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina ADD CONSTRAINT FK_D98EFD523C0CE86C FOREIGN KEY (resumen_id) REFERENCES novasoft_liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_renglon ADD CONSTRAINT FK_F14EA92F490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES novasoft_liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_resumen ADD CONSTRAINT FK_6803E7B5F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_resumen_renglon ADD CONSTRAINT FK_347C6075AEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES novasoft_liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_resumen_total ADD CONSTRAINT FK_CC95A1BDAEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES novasoft_liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_total ADD CONSTRAINT FK_362BFF65490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES novasoft_liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE novasoft_nomina ADD CONSTRAINT FK_F8FE35A5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE novasoft_nomina_detalle ADD CONSTRAINT FK_BB5B9DA58261CA50 FOREIGN KEY (nomina_id) REFERENCES novasoft_nomina (id)');
        $this->addSql('ALTER TABLE novasoft_trabajador_activo ADD CONSTRAINT FK_6A9F3837F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE novasoft_trabajador_activo ADD CONSTRAINT FK_6A9F3837952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE novasoft_trabajador_activo ADD CONSTRAINT FK_6A9F38372CC5C277 FOREIGN KEY (centro_costo_id) REFERENCES novasoft_centro_costo (codigo)');
        $this->addSql('ALTER TABLE se_certificado_ingresos ADD CONSTRAINT FK_CEA2E2C9DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_certificado_laboral ADD CONSTRAINT FK_FD052FFEDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_liquidacion_contrato ADD CONSTRAINT FK_9E479D74DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_nomina ADD CONSTRAINT FK_4AC9A1FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('DROP TABLE centro_costo');
        $this->addSql('DROP TABLE liquidacion_nomina');
        $this->addSql('DROP TABLE liquidacion_nomina_renglon');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen_renglon');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen_total');
        $this->addSql('DROP TABLE liquidacion_nomina_total');
        $this->addSql('DROP TABLE reporte_nomina');
        $this->addSql('DROP TABLE reporte_nomina_detalle');
        $this->addSql('DROP TABLE trabajador_activo');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE novasoft_trabajador_activo DROP FOREIGN KEY FK_6A9F38372CC5C277');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_renglon DROP FOREIGN KEY FK_F14EA92F490FD2A7');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_total DROP FOREIGN KEY FK_362BFF65490FD2A7');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina DROP FOREIGN KEY FK_D98EFD523C0CE86C');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_resumen_renglon DROP FOREIGN KEY FK_347C6075AEB8D475');
        $this->addSql('ALTER TABLE novasoft_liquidacion_nomina_resumen_total DROP FOREIGN KEY FK_CC95A1BDAEB8D475');
        $this->addSql('ALTER TABLE novasoft_nomina_detalle DROP FOREIGN KEY FK_BB5B9DA58261CA50');
        $this->addSql('CREATE TABLE centro_costo (codigo VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci, nombre VARCHAR(70) NOT NULL COLLATE utf8mb4_general_ci, PRIMARY KEY(codigo)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL COLLATE utf8mb4_general_ci, empleado_id INT NOT NULL, resumen_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, ingreso_basico INT DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, cargo_codigo VARCHAR(15) DEFAULT NULL COLLATE utf8mb4_general_ci, cargo VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_general_ci, cuenta VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_general_ci, INDEX IDX_8466F7AC952BE730 (empleado_id), INDEX IDX_8466F7ACF9D43F2A (convenio_id), INDEX IDX_8466F7AC3C0CE86C (resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, concepto_codigo VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, concepto VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, unidades DOUBLE PRECISION DEFAULT NULL, base DOUBLE PRECISION NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, centro_costo VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_general_ci, INDEX IDX_854B628C490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL COLLATE utf8mb4_general_ci, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, INDEX IDX_1C062C16F9D43F2A (convenio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, concepto VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, unidades INT DEFAULT NULL, base INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, INDEX IDX_823B9CD7AEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, unidades INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, neto INT DEFAULT NULL, UNIQUE INDEX UNIQ_A9FE2B89AEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liquidacion_nomina_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, unidades INT NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, neto INT NOT NULL, UNIQUE INDEX UNIQ_24AC4B99490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reporte_nomina (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, convenio_codigo_nombre VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, pension VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, salud VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, banco VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, cuenta VARCHAR(40) DEFAULT NULL COLLATE utf8mb4_general_ci, salario VARCHAR(60) DEFAULT NULL COLLATE utf8mb4_general_ci, cargo VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_general_ci, devengados_total VARCHAR(25) DEFAULT NULL COLLATE utf8mb4_general_ci, deducidos_total VARCHAR(25) DEFAULT NULL COLLATE utf8mb4_general_ci, neto VARCHAR(35) DEFAULT NULL COLLATE utf8mb4_general_ci, neto_texto VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, base_salario VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, base_retencion VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, met_retencion VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, porcentaje_retencion VARCHAR(30) DEFAULT NULL COLLATE utf8mb4_general_ci, dias_vacaciones_pend VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_general_ci, base_pension VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, INDEX IDX_5A7832B5DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reporte_nomina_detalle (id INT AUTO_INCREMENT NOT NULL, reporte_nomina_id INT NOT NULL, codigo VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, detalle VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci, cantidad VARCHAR(30) DEFAULT NULL COLLATE utf8mb4_general_ci, valor VARCHAR(40) DEFAULT NULL COLLATE utf8mb4_general_ci, tipo VARCHAR(8) NOT NULL COLLATE utf8mb4_general_ci, INDEX IDX_C049A933167A54EE (reporte_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE trabajador_activo (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL COLLATE utf8mb4_general_ci, empleado_id INT NOT NULL, centro_costo_id VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_general_ci, fecha_ingreso DATE DEFAULT NULL, ingreso_basico INT DEFAULT NULL, labor VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci, fecha_retiro DATE DEFAULT NULL, porcentaje_riesgo DOUBLE PRECISION DEFAULT NULL, cuenta VARCHAR(30) DEFAULT NULL COLLATE utf8mb4_general_ci, caja VARCHAR(30) DEFAULT NULL COLLATE utf8mb4_general_ci, promotora_salud VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_general_ci, admin_pension VARCHAR(70) DEFAULT NULL COLLATE utf8mb4_general_ci, INDEX IDX_EA8EC4CDF9D43F2A (convenio_id), UNIQUE INDEX UNIQ_EA8EC4CD952BE730 (empleado_id), INDEX IDX_EA8EC4CD2CC5C277 (centro_costo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7AC3C0CE86C FOREIGN KEY (resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7AC952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7ACF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE liquidacion_nomina_renglon ADD CONSTRAINT FK_854B628C490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen ADD CONSTRAINT FK_1C062C16F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_renglon ADD CONSTRAINT FK_823B9CD7AEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_total ADD CONSTRAINT FK_A9FE2B89AEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_total ADD CONSTRAINT FK_24AC4B99490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE reporte_nomina ADD CONSTRAINT FK_5A7832B5DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CD2CC5C277 FOREIGN KEY (centro_costo_id) REFERENCES centro_costo (codigo)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CD952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CDF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('DROP TABLE novasoft_centro_costo');
        $this->addSql('DROP TABLE novasoft_certificado_ingresos');
        $this->addSql('DROP TABLE novasoft_certificado_laboral');
        $this->addSql('DROP TABLE novasoft_liquidacion_contrato');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina_renglon');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina_resumen');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina_resumen_renglon');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina_resumen_total');
        $this->addSql('DROP TABLE novasoft_liquidacion_nomina_total');
        $this->addSql('DROP TABLE novasoft_nomina');
        $this->addSql('DROP TABLE novasoft_nomina_detalle');
        $this->addSql('DROP TABLE novasoft_trabajador_activo');
        $this->addSql('DROP TABLE se_certificado_ingresos');
        $this->addSql('DROP TABLE se_certificado_laboral');
        $this->addSql('DROP TABLE se_liquidacion_contrato');
        $this->addSql('DROP TABLE se_nomina');
    }
}
