<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824213605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evaluacion_progreso (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, evaluacion_id INT NOT NULL, modulo_id INT DEFAULT NULL, diapositiva_id INT DEFAULT NULL, pregunta_id INT DEFAULT NULL, pregunta_diapositiva_id INT DEFAULT NULL, culminacion DATETIME DEFAULT NULL, porcentaje_completitud SMALLINT NOT NULL, porcentaje_exito SMALLINT NOT NULL, descripcion VARCHAR(140) NOT NULL, UNIQUE INDEX UNIQ_F23A77ADDB38439E (usuario_id), INDEX IDX_F23A77ADE715F406 (evaluacion_id), INDEX IDX_F23A77ADC07F55F5 (modulo_id), INDEX IDX_F23A77ADFDCBF32B (diapositiva_id), INDEX IDX_F23A77AD31A5801E (pregunta_id), INDEX IDX_F23A77AD5F97925B (pregunta_diapositiva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77ADDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77ADE715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77ADC07F55F5 FOREIGN KEY (modulo_id) REFERENCES evaluacion_modulo (id)');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77ADFDCBF32B FOREIGN KEY (diapositiva_id) REFERENCES evaluacion_diapositiva (id)');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77AD31A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id)');
        $this->addSql('ALTER TABLE evaluacion_progreso ADD CONSTRAINT FK_F23A77AD5F97925B FOREIGN KEY (pregunta_diapositiva_id) REFERENCES evaluacion_diapositiva (id)');
        $this->addSql('DROP TABLE progreso');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE progreso (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, evaluacion_id INT NOT NULL, modulo_id INT DEFAULT NULL, diapositiva_id INT DEFAULT NULL, pregunta_id INT DEFAULT NULL, culminacion DATETIME DEFAULT NULL, porcentaje_completitud SMALLINT NOT NULL, porcentaje_exito SMALLINT NOT NULL, descripcion VARCHAR(140) NOT NULL COLLATE utf8mb4_general_ci, INDEX IDX_3600AE09E715F406 (evaluacion_id), INDEX IDX_3600AE09FDCBF32B (diapositiva_id), UNIQUE INDEX UNIQ_3600AE09DB38439E (usuario_id), INDEX IDX_3600AE09C07F55F5 (modulo_id), INDEX IDX_3600AE0931A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE0931A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09C07F55F5 FOREIGN KEY (modulo_id) REFERENCES evaluacion_modulo (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09FDCBF32B FOREIGN KEY (diapositiva_id) REFERENCES evaluacion_diapositiva (id)');
        $this->addSql('DROP TABLE evaluacion_progreso');
    }
}
