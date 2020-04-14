<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414103338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE napi_se_liquidacion_contrato (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, identificacion VARCHAR(12) NOT NULL, fecha_ingreso DATE NOT NULL, fecha_retiro DATE NOT NULL, contrato INT NOT NULL, fecha_corte DATE NOT NULL, INDEX IDX_96FEFE56DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE napi_se_liquidacion_contrato ADD CONSTRAINT FK_96FEFE56DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE napi_se_certificado_ingresos DROP FOREIGN KEY FK_D3360FE3DB38439E');
        $this->addSql('DROP INDEX idx_d3360fe3db38439e ON napi_se_certificado_ingresos');
        $this->addSql('CREATE INDEX IDX_C61B81EBDB38439E ON napi_se_certificado_ingresos (usuario_id)');
        $this->addSql('ALTER TABLE napi_se_certificado_ingresos ADD CONSTRAINT FK_D3360FE3DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_certificado_ingresos CHANGE source_id source_id VARCHAR(28) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE napi_se_liquidacion_contrato');
        $this->addSql('ALTER TABLE napi_se_certificado_ingresos DROP FOREIGN KEY FK_C61B81EBDB38439E');
        $this->addSql('DROP INDEX idx_c61b81ebdb38439e ON napi_se_certificado_ingresos');
        $this->addSql('CREATE INDEX IDX_D3360FE3DB38439E ON napi_se_certificado_ingresos (usuario_id)');
        $this->addSql('ALTER TABLE napi_se_certificado_ingresos ADD CONSTRAINT FK_C61B81EBDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_certificado_ingresos CHANGE source_id source_id VARCHAR(27) NOT NULL COLLATE utf8mb4_general_ci');
    }
}
