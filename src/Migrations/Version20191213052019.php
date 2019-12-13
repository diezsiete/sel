<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191213052019 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE autoliquidacion_progreso (id INT AUTO_INCREMENT NOT NULL, porcentaje INT NOT NULL, last_message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE centro_costo (codigo VARCHAR(20) NOT NULL, nombre VARCHAR(70) NOT NULL, PRIMARY KEY(codigo)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, empleado_id INT NOT NULL, resumen_id INT DEFAULT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, ingreso_basico INT DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, cargo_codigo VARCHAR(15) DEFAULT NULL, cargo VARCHAR(70) DEFAULT NULL, cuenta VARCHAR(50) DEFAULT NULL, INDEX IDX_8466F7ACF9D43F2A (convenio_id), INDEX IDX_8466F7AC952BE730 (empleado_id), INDEX IDX_8466F7AC3C0CE86C (resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, concepto_codigo VARCHAR(10) DEFAULT NULL, concepto VARCHAR(140) DEFAULT NULL, unidades DOUBLE PRECISION DEFAULT NULL, base DOUBLE PRECISION NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, centro_costo VARCHAR(70) DEFAULT NULL, INDEX IDX_854B628C490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, fecha_inicial DATE NOT NULL, fecha_final DATE NOT NULL, INDEX IDX_1C062C16F9D43F2A (convenio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen_renglon (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, concepto VARCHAR(255) DEFAULT NULL, unidades INT DEFAULT NULL, base INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, INDEX IDX_823B9CD7AEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina_resumen_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_resumen_id INT NOT NULL, unidades INT DEFAULT NULL, devengos INT DEFAULT NULL, deducciones INT DEFAULT NULL, neto INT DEFAULT NULL, UNIQUE INDEX UNIQ_A9FE2B89AEB8D475 (liquidacion_nomina_resumen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liquidacion_nomina_total (id INT AUTO_INCREMENT NOT NULL, liquidacion_nomina_id INT NOT NULL, unidades INT NOT NULL, devengos INT NOT NULL, deducciones INT NOT NULL, neto INT NOT NULL, UNIQUE INDEX UNIQ_24AC4B99490FD2A7 (liquidacion_nomina_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trabajador_activo (id INT AUTO_INCREMENT NOT NULL, convenio_id VARCHAR(45) NOT NULL, empleado_id INT NOT NULL, centro_costo_id VARCHAR(20) DEFAULT NULL, fecha_ingreso DATE DEFAULT NULL, ingreso_basico INT DEFAULT NULL, labor VARCHAR(255) DEFAULT NULL, fecha_retiro DATE DEFAULT NULL, porcentaje_riesgo DOUBLE PRECISION DEFAULT NULL, cuenta VARCHAR(30) DEFAULT NULL, caja VARCHAR(30) DEFAULT NULL, promotora_salud VARCHAR(70) DEFAULT NULL, admin_pension VARCHAR(70) DEFAULT NULL, INDEX IDX_EA8EC4CDF9D43F2A (convenio_id), UNIQUE INDEX UNIQ_EA8EC4CD952BE730 (empleado_id), INDEX IDX_EA8EC4CD2CC5C277 (centro_costo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7ACF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7AC952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina ADD CONSTRAINT FK_8466F7AC3C0CE86C FOREIGN KEY (resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_renglon ADD CONSTRAINT FK_854B628C490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen ADD CONSTRAINT FK_1C062C16F9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_renglon ADD CONSTRAINT FK_823B9CD7AEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_total ADD CONSTRAINT FK_A9FE2B89AEB8D475 FOREIGN KEY (liquidacion_nomina_resumen_id) REFERENCES liquidacion_nomina_resumen (id)');
        $this->addSql('ALTER TABLE liquidacion_nomina_total ADD CONSTRAINT FK_24AC4B99490FD2A7 FOREIGN KEY (liquidacion_nomina_id) REFERENCES liquidacion_nomina (id)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CDF9D43F2A FOREIGN KEY (convenio_id) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CD952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE trabajador_activo ADD CONSTRAINT FK_EA8EC4CD2CC5C277 FOREIGN KEY (centro_costo_id) REFERENCES centro_costo (codigo)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trabajador_activo DROP FOREIGN KEY FK_EA8EC4CD2CC5C277');
        $this->addSql('ALTER TABLE liquidacion_nomina_renglon DROP FOREIGN KEY FK_854B628C490FD2A7');
        $this->addSql('ALTER TABLE liquidacion_nomina_total DROP FOREIGN KEY FK_24AC4B99490FD2A7');
        $this->addSql('ALTER TABLE liquidacion_nomina DROP FOREIGN KEY FK_8466F7AC3C0CE86C');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_renglon DROP FOREIGN KEY FK_823B9CD7AEB8D475');
        $this->addSql('ALTER TABLE liquidacion_nomina_resumen_total DROP FOREIGN KEY FK_A9FE2B89AEB8D475');
        $this->addSql('DROP TABLE autoliquidacion_progreso');
        $this->addSql('DROP TABLE centro_costo');
        $this->addSql('DROP TABLE liquidacion_nomina');
        $this->addSql('DROP TABLE liquidacion_nomina_renglon');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen_renglon');
        $this->addSql('DROP TABLE liquidacion_nomina_resumen_total');
        $this->addSql('DROP TABLE liquidacion_nomina_total');
        $this->addSql('DROP TABLE trabajador_activo');
    }
}
