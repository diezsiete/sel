<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612204914 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B4642BBA9760');
        $this->addSql('DROP TABLE vacante_vigencia');
        $this->addSql('DROP INDEX IDX_B8B6B4642BBA9760 ON vacante');
        $this->addSql('ALTER TABLE vacante ADD activa TINYINT(1) NOT NULL, ADD vigencia SMALLINT NOT NULL, ADD archivada TINYINT(1) NOT NULL, DROP vigencia_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_vigencia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(20) NOT NULL COLLATE utf8mb4_general_ci, interval_spec VARCHAR(10) NOT NULL COLLATE utf8mb4_general_ci, mysql_interval VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, mysql_interval_value SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vacante ADD vigencia_id INT NOT NULL, DROP activa, DROP vigencia, DROP archivada');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B4642BBA9760 FOREIGN KEY (vigencia_id) REFERENCES vacante_vigencia (id)');
        $this->addSql('CREATE INDEX IDX_B8B6B4642BBA9760 ON vacante (vigencia_id)');
    }
}
