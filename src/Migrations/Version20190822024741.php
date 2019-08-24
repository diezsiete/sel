<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190822024741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluacion_diapositiva DROP css');
        $this->addSql('ALTER TABLE evaluacion ADD guardar_proceso TINYINT(1) NOT NULL, DROP accion_fallo, CHANGE guardar_en_proceso repetir_en_fallo TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluacion ADD accion_fallo SMALLINT NOT NULL, ADD guardar_en_proceso TINYINT(1) NOT NULL, DROP repetir_en_fallo, DROP guardar_proceso');
        $this->addSql('ALTER TABLE evaluacion_diapositiva ADD css VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}
