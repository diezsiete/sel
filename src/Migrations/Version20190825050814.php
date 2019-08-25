<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190825050814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE evaluacion_respuesta (id INT AUTO_INCREMENT NOT NULL, progreso_id INT NOT NULL, pregunta_id INT NOT NULL, respondida_en DATETIME NOT NULL, widget VARCHAR(255) NOT NULL, INDEX IDX_BD1198C659146FEE (progreso_id), INDEX IDX_BD1198C631A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion_respuesta_opcion (id INT AUTO_INCREMENT NOT NULL, respuesta_id INT NOT NULL, pregunta_opcion_id INT NOT NULL, INDEX IDX_821B9EFFD9BA57A2 (respuesta_id), INDEX IDX_821B9EFF3B89128 (pregunta_opcion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluacion_respuesta ADD CONSTRAINT FK_BD1198C659146FEE FOREIGN KEY (progreso_id) REFERENCES evaluacion_progreso (id)');
        $this->addSql('ALTER TABLE evaluacion_respuesta ADD CONSTRAINT FK_BD1198C631A5801E FOREIGN KEY (pregunta_id) REFERENCES evaluacion_pregunta (id)');
        $this->addSql('ALTER TABLE evaluacion_respuesta_opcion ADD CONSTRAINT FK_821B9EFFD9BA57A2 FOREIGN KEY (respuesta_id) REFERENCES evaluacion_respuesta (id)');
        $this->addSql('ALTER TABLE evaluacion_respuesta_opcion ADD CONSTRAINT FK_821B9EFF3B89128 FOREIGN KEY (pregunta_opcion_id) REFERENCES evaluacion_pregunta_opcion (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluacion_respuesta_opcion DROP FOREIGN KEY FK_821B9EFFD9BA57A2');
        $this->addSql('DROP TABLE evaluacion_respuesta');
        $this->addSql('DROP TABLE evaluacion_respuesta_opcion');
    }
}
