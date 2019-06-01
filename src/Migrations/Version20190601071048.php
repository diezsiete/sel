<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601071048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, date DATE NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, requisitos LONGTEXT DEFAULT NULL, vacantes_cantidad SMALLINT NOT NULL, salario_neto VARCHAR(31) DEFAULT NULL, salario_adicion INT DEFAULT NULL, salario_adicion_concepto VARCHAR(60) DEFAULT NULL, salario_publicar TINYINT(1) NOT NULL, nivel_academico_curso TINYINT(1) NOT NULL, idioma_porcentaje SMALLINT DEFAULT NULL, genero SMALLINT DEFAULT NULL, facebook VARCHAR(200) DEFAULT NULL, twitter VARCHAR(140) DEFAULT NULL, publicada TINYINT(1) NOT NULL, empresa VARCHAR(11) DEFAULT NULL, nivel VARCHAR(15) NOT NULL, subnivel VARCHAR(40) NOT NULL, contrato_tipo VARCHAR(27) DEFAULT NULL, intensidad_horaria VARCHAR(8) DEFAULT NULL, salario_rango VARCHAR(12) NOT NULL, nivel_academico VARCHAR(30) NOT NULL, experiencia VARCHAR(16) NOT NULL, idioma VARCHAR(10) DEFAULT NULL, INDEX IDX_B8B6B464DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vacante');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
