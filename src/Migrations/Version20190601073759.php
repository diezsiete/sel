<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601073759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE area (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cargo (id VARCHAR(7) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ciudad (id VARCHAR(7) NOT NULL, pais_id VARCHAR(7) NOT NULL, dpto_id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, INDEX IDX_8E86059EC604D5C6 (pais_id), INDEX IDX_8E86059E1FA00731 (dpto_id), PRIMARY KEY(id, pais_id, dpto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dpto (id VARCHAR(7) NOT NULL, pais_id VARCHAR(7) NOT NULL, nombre VARCHAR(45) NOT NULL, INDEX IDX_57AF1723C604D5C6 (pais_id), PRIMARY KEY(id, pais_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pais (id VARCHAR(7) NOT NULL, nombre VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059EC604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE ciudad ADD CONSTRAINT FK_8E86059E1FA00731 FOREIGN KEY (dpto_id) REFERENCES dpto (id)');
        $this->addSql('ALTER TABLE dpto ADD CONSTRAINT FK_57AF1723C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059E1FA00731');
        $this->addSql('ALTER TABLE ciudad DROP FOREIGN KEY FK_8E86059EC604D5C6');
        $this->addSql('ALTER TABLE dpto DROP FOREIGN KEY FK_57AF1723C604D5C6');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE ciudad');
        $this->addSql('DROP TABLE dpto');
        $this->addSql('DROP TABLE pais');
    }
}
