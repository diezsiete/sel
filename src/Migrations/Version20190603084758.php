<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603084758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE red_social (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, tipo SMALLINT NOT NULL, cuenta VARCHAR(145) NOT NULL, INDEX IDX_465D8E03B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referencia (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, tipo SMALLINT NOT NULL, nombre VARCHAR(105) NOT NULL, ocupacion VARCHAR(145) NOT NULL, parentesco VARCHAR(45) DEFAULT NULL, celular BIGINT NOT NULL, entidad VARCHAR(100) DEFAULT NULL, telefono INT DEFAULT NULL, direccion VARCHAR(50) DEFAULT NULL, INDEX IDX_C01213D8B83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vivienda (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, pais_id INT NOT NULL, dpto_id INT DEFAULT NULL, ciudad_id INT DEFAULT NULL, direccion VARCHAR(40) NOT NULL, estrato SMALLINT DEFAULT NULL, tipo_vivienda SMALLINT DEFAULT NULL, tenedor SMALLINT DEFAULT NULL, vivienda_actual TINYINT(1) DEFAULT NULL, INDEX IDX_2DFFABDEB83428F3 (hv_id), INDEX IDX_2DFFABDEC604D5C6 (pais_id), INDEX IDX_2DFFABDE1FA00731 (dpto_id), INDEX IDX_2DFFABDEE8608214 (ciudad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE red_social ADD CONSTRAINT FK_465D8E03B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDE1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE vivienda ADD CONSTRAINT FK_2DFFABDEE8608214 FOREIGN KEY (ciudad_id) REFERENCES ciudad (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE red_social');
        $this->addSql('DROP TABLE referencia');
        $this->addSql('DROP TABLE vivienda');
    }
}
