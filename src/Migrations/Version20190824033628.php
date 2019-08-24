<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190824033628 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE progreso (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, evaluacion_id INT NOT NULL, modulo_id INT DEFAULT NULL, diapositiva_id INT DEFAULT NULL, pregunta_id INT DEFAULT NULL, culminacion DATETIME DEFAULT NULL, porcentaje_completitud SMALLINT NOT NULL, porcentaje_exito SMALLINT NOT NULL, descripcion VARCHAR(140) NOT NULL, UNIQUE INDEX UNIQ_3600AE09DB38439E (usuario_id), INDEX IDX_3600AE09E715F406 (evaluacion_id), INDEX IDX_3600AE09C07F55F5 (modulo_id), INDEX IDX_3600AE09FDCBF32B (diapositiva_id), INDEX IDX_3600AE0931A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09C07F55F5 FOREIGN KEY (modulo_id) REFERENCES evaluacion_modulo (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE09FDCBF32B FOREIGN KEY (diapositiva_id) REFERENCES evaluacion_diapositiva (id)');
        $this->addSql('ALTER TABLE progreso ADD CONSTRAINT FK_3600AE0931A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id)');
        $this->addSql('ALTER TABLE evaluacion_pregunta ADD mensaje_ayuda VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE progreso');
        $this->addSql('ALTER TABLE evaluacion_pregunta DROP mensaje_ayuda');
    }
}
