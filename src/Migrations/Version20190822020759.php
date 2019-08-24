<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190822020759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evaluacion_diapositiva (id INT AUTO_INCREMENT NOT NULL, presentacion_id INT NOT NULL, indice SMALLINT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, css VARCHAR(255) DEFAULT NULL, INDEX IDX_8D56338291BDCCB (presentacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre VARCHAR(60) NOT NULL, slug VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, minimo_porcentaje_exito SMALLINT NOT NULL, accion_fallo SMALLINT NOT NULL, guardar_en_proceso TINYINT(1) NOT NULL, INDEX IDX_DEEDCA53DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_modulo (id INT AUTO_INCREMENT NOT NULL, evaluacion_id INT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, indice SMALLINT NOT NULL, numero_intentos SMALLINT NOT NULL, repetir_en_fallo TINYINT(1) NOT NULL, INDEX IDX_A1432B89E715F406 (evaluacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_modulo_diapositiva (modulo_id INT NOT NULL, diapositiva_id INT NOT NULL, INDEX IDX_45EDA40EC07F55F5 (modulo_id), INDEX IDX_45EDA40EFDCBF32B (diapositiva_id), PRIMARY KEY(modulo_id, diapositiva_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_pregunta (id INT AUTO_INCREMENT NOT NULL, evaluacion_id INT NOT NULL, modulo_id INT DEFAULT NULL, indice SMALLINT NOT NULL, texto LONGTEXT DEFAULT NULL, porcentaje_exito SMALLINT NOT NULL, numero_intentos SMALLINT DEFAULT NULL, widget VARCHAR(255) NOT NULL, INDEX IDX_6ADA3853E715F406 (evaluacion_id), INDEX IDX_6ADA3853C07F55F5 (modulo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_pregunta_diapositiva (pregunta_id INT NOT NULL, diapositiva_id INT NOT NULL, INDEX IDX_C35EE5FF31A5801E (pregunta_id), INDEX IDX_C35EE5FFFDCBF32B (diapositiva_id), PRIMARY KEY(pregunta_id, diapositiva_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_pregunta_opcion (id INT AUTO_INCREMENT NOT NULL, pregunta_id INT NOT NULL, texto LONGTEXT NOT NULL, respuesta SMALLINT DEFAULT NULL, INDEX IDX_3B18E6031A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_presentacion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_AF59DF88DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluacion_diapositiva ADD CONSTRAINT FK_8D56338291BDCCB FOREIGN KEY (presentacion_id) REFERENCES evaluacion_presentacion (id)');
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA53DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE evaluacion_modulo ADD CONSTRAINT FK_A1432B89E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE evaluacion_modulo_diapositiva ADD CONSTRAINT FK_45EDA40EC07F55F5 FOREIGN KEY (modulo_id) REFERENCES evaluacion_modulo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_modulo_diapositiva ADD CONSTRAINT FK_45EDA40EFDCBF32B FOREIGN KEY (diapositiva_id) REFERENCES evaluacion_diapositiva (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_pregunta ADD CONSTRAINT FK_6ADA3853E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE evaluacion_pregunta ADD CONSTRAINT FK_6ADA3853C07F55F5 FOREIGN KEY (modulo_id) REFERENCES evaluacion_modulo (id)');
        $this->addSql('ALTER TABLE evaluacion_pregunta_diapositiva ADD CONSTRAINT FK_C35EE5FF31A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_pregunta_diapositiva ADD CONSTRAINT FK_C35EE5FFFDCBF32B FOREIGN KEY (diapositiva_id) REFERENCES evaluacion_diapositiva (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_pregunta_opcion ADD CONSTRAINT FK_3B18E6031A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id)');
        $this->addSql('ALTER TABLE evaluacion_presentacion ADD CONSTRAINT FK_AF59DF88DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluacion_modulo_diapositiva DROP FOREIGN KEY FK_45EDA40EFDCBF32B');
        $this->addSql('ALTER TABLE evaluacion_pregunta_diapositiva DROP FOREIGN KEY FK_C35EE5FFFDCBF32B');
        $this->addSql('ALTER TABLE evaluacion_modulo DROP FOREIGN KEY FK_A1432B89E715F406');
        $this->addSql('ALTER TABLE evaluacion_pregunta DROP FOREIGN KEY FK_6ADA3853E715F406');
        $this->addSql('ALTER TABLE evaluacion_modulo_diapositiva DROP FOREIGN KEY FK_45EDA40EC07F55F5');
        $this->addSql('ALTER TABLE evaluacion_pregunta DROP FOREIGN KEY FK_6ADA3853C07F55F5');
        $this->addSql('ALTER TABLE evaluacion_pregunta_diapositiva DROP FOREIGN KEY FK_C35EE5FF31A5801E');
        $this->addSql('ALTER TABLE evaluacion_pregunta_opcion DROP FOREIGN KEY FK_3B18E6031A5801E');
        $this->addSql('ALTER TABLE evaluacion_diapositiva DROP FOREIGN KEY FK_8D56338291BDCCB');
        $this->addSql('DROP TABLE evaluacion_diapositiva');
        $this->addSql('DROP TABLE evaluacion');
        $this->addSql('DROP TABLE evaluacion_modulo');
        $this->addSql('DROP TABLE evaluacion_modulo_diapositiva');
        $this->addSql('DROP TABLE evaluacion_pregunta');
        $this->addSql('DROP TABLE evaluacion_pregunta_diapositiva');
        $this->addSql('DROP TABLE evaluacion_pregunta_opcion');
        $this->addSql('DROP TABLE evaluacion_presentacion');
    }
}
