<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603074822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hv (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, nac_pais_id INT NOT NULL, nac_dpto_id INT DEFAULT NULL, nac_ciudad_id INT DEFAULT NULL, ident_pais_id INT NOT NULL, ident_dpto_id INT DEFAULT NULL, ident_ciudad_id INT DEFAULT NULL, resi_pais_id INT NOT NULL, resi_dpto_id INT DEFAULT NULL, resi_ciudad_id INT DEFAULT NULL, nivel_academico_id VARCHAR(7) NOT NULL, genero SMALLINT NOT NULL, estado_civil SMALLINT NOT NULL, barrio VARCHAR(45) DEFAULT NULL, direccion VARCHAR(60) DEFAULT NULL, telefono BIGINT DEFAULT NULL, celular BIGINT DEFAULT NULL, grupo_sanguineo VARCHAR(2) DEFAULT NULL, factor_rh VARCHAR(1) DEFAULT NULL, nacionalidad SMALLINT DEFAULT NULL, email_alt VARCHAR(100) DEFAULT NULL, aspiracion_sueldo BIGINT DEFAULT NULL, estatura DOUBLE PRECISION DEFAULT NULL, peso DOUBLE PRECISION DEFAULT NULL, personas_cargo SMALLINT DEFAULT NULL, UNIQUE INDEX UNIQ_559B2759DB38439E (usuario_id), INDEX IDX_559B2759E580352F (nac_pais_id), INDEX IDX_559B27593C24E7D8 (nac_dpto_id), INDEX IDX_559B2759C7D60B4D (nac_ciudad_id), INDEX IDX_559B275939CAB6F1 (ident_pais_id), INDEX IDX_559B2759E06E6406 (ident_dpto_id), INDEX IDX_559B2759ACDCED0C (ident_ciudad_id), INDEX IDX_559B2759A8F701C8 (resi_pais_id), INDEX IDX_559B27597153D33F (resi_dpto_id), INDEX IDX_559B2759F75DD7A4 (resi_ciudad_id), INDEX IDX_559B2759C21F5FA8 (nivel_academico_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hv_adjunto (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, filename VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CBD56489B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759E580352F FOREIGN KEY (nac_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27593C24E7D8 FOREIGN KEY (nac_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759C7D60B4D FOREIGN KEY (nac_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B275939CAB6F1 FOREIGN KEY (ident_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759E06E6406 FOREIGN KEY (ident_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759ACDCED0C FOREIGN KEY (ident_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759A8F701C8 FOREIGN KEY (resi_pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27597153D33F FOREIGN KEY (resi_dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759F75DD7A4 FOREIGN KEY (resi_ciudad_id) REFERENCES ciudad (id)');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id)');
        $this->addSql('ALTER TABLE hv_adjunto ADD CONSTRAINT FK_CBD56489B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv_adjunto DROP FOREIGN KEY FK_CBD56489B83428F3');
        $this->addSql('DROP TABLE hv');
        $this->addSql('DROP TABLE hv_adjunto');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
