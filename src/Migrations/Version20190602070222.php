<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190602070222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464C21F5FA8');
        $this->addSql('DROP TABLE nivel_academico');
        $this->addSql('DROP INDEX IDX_B8B6B464C21F5FA8 ON vacante');
        $this->addSql('ALTER TABLE vacante DROP nivel_academico_id, CHANGE contrato_tipo contrato_tipo SMALLINT DEFAULT NULL, CHANGE salario_rango salario_rango SMALLINT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE nivel_academico (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(35) NOT NULL COLLATE utf8mb4_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vacante ADD nivel_academico_id INT NOT NULL, CHANGE contrato_tipo contrato_tipo VARCHAR(27) DEFAULT NULL COLLATE utf8mb4_general_ci, CHANGE salario_rango salario_rango VARCHAR(12) NOT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id)');
        $this->addSql('CREATE INDEX IDX_B8B6B464C21F5FA8 ON vacante (nivel_academico_id)');
    }
}
