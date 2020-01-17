<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200117070204 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE report_cache (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, source VARCHAR(8) NOT NULL, last_update DATETIME NOT NULL, report VARCHAR(20) NOT NULL, INDEX IDX_6B5B6C9EDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_cache ADD CONSTRAINT FK_6B5B6C9EDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE se_liquidacion_contrato ADD convenio VARCHAR(105) DEFAULT NULL, DROP cargo');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE report_cache');
        $this->addSql('ALTER TABLE se_liquidacion_contrato ADD cargo VARCHAR(60) DEFAULT NULL COLLATE utf8mb4_general_ci, DROP convenio');
    }
}
