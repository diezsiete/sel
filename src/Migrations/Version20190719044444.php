<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190719044444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE autoliquidacion (id INT AUTO_INCREMENT NOT NULL, convenio_codigo VARCHAR(45) NOT NULL, usuario_id INT NOT NULL, periodo DATE NOT NULL, fecha_ejecucion DATETIME NOT NULL, porcentaje_ejecucion SMALLINT NOT NULL, email_sended TINYINT(1) NOT NULL, INDEX IDX_19E64F7A32717B6D (convenio_codigo), INDEX IDX_19E64F7ADB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autoliquidacion ADD CONSTRAINT FK_19E64F7A32717B6D FOREIGN KEY (convenio_codigo) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE autoliquidacion ADD CONSTRAINT FK_19E64F7ADB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE hv CHANGE grupo_sanguineo grupo_sanguineo VARCHAR(2) NOT NULL, CHANGE factor_rh factor_rh VARCHAR(1) NOT NULL, CHANGE nacionalidad nacionalidad SMALLINT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B8B6B464989D9B62 ON vacante (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE autoliquidacion');
        $this->addSql('ALTER TABLE hv CHANGE grupo_sanguineo grupo_sanguineo VARCHAR(2) DEFAULT NULL COLLATE utf8mb4_general_ci, CHANGE factor_rh factor_rh VARCHAR(1) DEFAULT NULL COLLATE utf8mb4_general_ci, CHANGE nacionalidad nacionalidad SMALLINT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_B8B6B464989D9B62 ON vacante');
    }
}
