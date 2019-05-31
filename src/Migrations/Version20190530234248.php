<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190530234248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE usuario_old');
        $this->addSql('ALTER TABLE usuario ADD type INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE usuario_old (id INT UNSIGNED AUTO_INCREMENT NOT NULL, ident VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci, email VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci, pss BLOB DEFAULT NULL, primer_nombre VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci, segundo_nombre VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci, primer_apellido VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci, segundo_apellido VARCHAR(45) DEFAULT NULL COLLATE utf8mb4_general_ci, nacimiento DATE DEFAULT NULL, roles TEXT DEFAULT NULL COLLATE utf8mb4_general_ci, creacion DATETIME DEFAULT CURRENT_TIMESTAMP, ultimo_login DATETIME DEFAULT NULL, activo TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX cedula_UNIQUE (ident), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE usuario DROP type');
    }
}