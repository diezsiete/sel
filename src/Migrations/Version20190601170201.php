<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601170201 extends AbstractMigration
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
        $this->addSql('CREATE TABLE ciudad (id VARCHAR(7) NOT NULL, dpto_id VARCHAR(7) NOT NULL, pais_id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, INDEX IDX_8E86059EC604D5C6 (pais_id), INDEX IDX_8E86059E1FA00731C604D5C6 (dpto_id, pais_id), PRIMARY KEY(id, pais_id, dpto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dpto (id VARCHAR(7) NOT NULL, pais_id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, INDEX IDX_57AF1723C604D5C6 (pais_id), PRIMARY KEY(id, pais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idioma (id VARCHAR(3) NOT NULL, nombre VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licencia_conduccion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nivel_academico (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(35) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pais (id VARCHAR(7) NOT NULL, nombre VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profesion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_area (vacante_id INT NOT NULL, area_id VARCHAR(7) NOT NULL, INDEX IDX_99DCE3218B34DB71 (vacante_id), INDEX IDX_99DCE321BD0F409C (area_id), PRIMARY KEY(vacante_id, area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_cargo (vacante_id INT NOT NULL, cargo_id VARCHAR(7) NOT NULL, INDEX IDX_34A0E69B8B34DB71 (vacante_id), INDEX IDX_34A0E69B813AC380 (cargo_id), PRIMARY KEY(vacante_id, cargo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_licencia_conduccion (vacante_id INT NOT NULL, licencia_conduccion_id INT NOT NULL, INDEX IDX_EE67F3E38B34DB71 (vacante_id), INDEX IDX_EE67F3E3A8764065 (licencia_conduccion_id), PRIMARY KEY(vacante_id, licencia_conduccion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_profesion (vacante_id INT NOT NULL, profesion_id INT NOT NULL, INDEX IDX_FFA6E76B8B34DB71 (vacante_id), INDEX IDX_FFA6E76BC5AF4D0F (profesion_id), PRIMARY KEY(vacante_id, profesion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_usuario (vacante_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_3A92276F8B34DB71 (vacante_id), INDEX IDX_3A92276FDB38439E (usuario_id), PRIMARY KEY(vacante_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_ciudad (vacante_id INT NOT NULL, ciudad_id VARCHAR(7) NOT NULL, pais_id VARCHAR(7) NOT NULL, dpto_id VARCHAR(7) NOT NULL, INDEX IDX_CE5640498B34DB71 (vacante_id), INDEX IDX_CE564049E8608214C604D5C61FA00731 (ciudad_id, pais_id, dpto_id), PRIMARY KEY(vacante_id, ciudad_id, pais_id, dpto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_red_social (id INT AUTO_INCREMENT NOT NULL, vacante_id INT NOT NULL, nombre VARCHAR(20) NOT NULL, id_post VARCHAR(255) DEFAULT NULL, INDEX IDX_7C6BC4878B34DB71 (vacante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_vigencia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(20) NOT NULL, interval_spec VARCHAR(10) NOT NULL, mysql_interval VARCHAR(7) NOT NULL, mysql_interval_value SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731C604D5C6 FOREIGN KEY (dpto_id, pais_id) REFERENCES dpto (id, pais_id)');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE vacante_area ADD CONSTRAINT FK_99DCE3218B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_area ADD CONSTRAINT FK_99DCE321BD0F409C FOREIGN KEY (area_id) REFERENCES area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E38B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E3A8764065 FOREIGN KEY (licencia_conduccion_id) REFERENCES licencia_conduccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76BC5AF4D0F FOREIGN KEY (profesion_id) REFERENCES profesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276F8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE5640498B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id)');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE564049E8608214C604D5C61FA00731 FOREIGN KEY (ciudad_id, pais_id, dpto_id) REFERENCES ciudad (id, pais_id, dpto_id)');
        $this->addSql('ALTER TABLE vacante_red_social ADD CONSTRAINT FK_7C6BC4878B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id)');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DEDC0611 FOREIGN KEY (idioma_id) REFERENCES idioma (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B4642BBA9760 FOREIGN KEY (vigencia_id) REFERENCES vacante_vigencia (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante_area DROP FOREIGN KEY FK_99DCE321BD0F409C');
        $this->addSql('ALTER TABLE vacante_cargo DROP FOREIGN KEY FK_34A0E69B813AC380');
        $this->addSql('ALTER TABLE vacante_ciudad DROP FOREIGN KEY FK_CE564049E8608214C604D5C61FA00731');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731C604D5C6');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DEDC0611');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion DROP FOREIGN KEY FK_EE67F3E3A8764065');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464C21F5FA8');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('ALTER TABLE vacante_profesion DROP FOREIGN KEY FK_FFA6E76BC5AF4D0F');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B4642BBA9760');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE ciudad');
        $this->addSql('DROP TABLE dpto');
        $this->addSql('DROP TABLE idioma');
        $this->addSql('DROP TABLE licencia_conduccion');
        $this->addSql('DROP TABLE nivel_academico');
        $this->addSql('DROP TABLE pais');
        $this->addSql('DROP TABLE profesion');
        $this->addSql('DROP TABLE vacante_area');
        $this->addSql('DROP TABLE vacante_cargo');
        $this->addSql('DROP TABLE vacante_licencia_conduccion');
        $this->addSql('DROP TABLE vacante_profesion');
        $this->addSql('DROP TABLE vacante_usuario');
        $this->addSql('DROP TABLE vacante_ciudad');
        $this->addSql('DROP TABLE vacante_red_social');
        $this->addSql('DROP TABLE vacante_vigencia');
        $this->addSql('ALTER TABLE reporte_nomina_detalle DROP FOREIGN KEY FK_C049A933167A54EE');
        $this->addSql('ALTER TABLE reporte_nomina_detalle ADD CONSTRAINT FK_C049A933167A54EE FOREIGN KEY (reporte_nomina_id) REFERENCES reporte_nomina (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DB38439E');
    }
}
