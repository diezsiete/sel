<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603055207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante_ciudad DROP FOREIGN KEY FK_CE564049E8608214C604D5C61FA00731');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731C604D5C6');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('DROP TABLE ciudad');
        $this->addSql('DROP TABLE dpto');
        $this->addSql('DROP TABLE pais');
        $this->addSql('DROP TABLE vacante_ciudad');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ciudad (id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, dpto_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, pais_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, nombre VARCHAR(45) NOT NULL COLLATE utf8mb4_general_ci, INDEX IDX_8E86059E1FA00731C604D5C6 (dpto_id, pais_id), INDEX IDX_8E86059EC604D5C6 (pais_id), PRIMARY KEY(id, pais_id, dpto_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE dpto (id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, pais_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, nombre VARCHAR(45) NOT NULL COLLATE utf8mb4_general_ci, INDEX IDX_57AF1723C604D5C6 (pais_id), PRIMARY KEY(id, pais_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pais (id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, nombre VARCHAR(60) NOT NULL COLLATE utf8mb4_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vacante_ciudad (vacante_id INT NOT NULL, ciudad_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, pais_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, dpto_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, INDEX IDX_CE5640498B34DB71 (vacante_id), INDEX IDX_CE564049E8608214C604D5C61FA00731 (ciudad_id, pais_id, dpto_id), PRIMARY KEY(vacante_id, ciudad_id, pais_id, dpto_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731C604D5C6 FOREIGN KEY (dpto_id, pais_id) REFERENCES dpto (id, pais_id)');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE5640498B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id)');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE564049E8608214C604D5C61FA00731 FOREIGN KEY (ciudad_id, pais_id, dpto_id) REFERENCES ciudad (id, pais_id, dpto_id)');
    }
}
