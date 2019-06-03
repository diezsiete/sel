<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603083442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DEDC0611');
        $this->addSql('DROP TABLE idioma');
        $this->addSql('DROP INDEX IDX_B8B6B464DEDC0611 ON vacante');
        $this->addSql('ALTER TABLE vacante DROP idioma_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE idioma (id VARCHAR(3) NOT NULL COLLATE utf8mb4_general_ci, nombre VARCHAR(10) NOT NULL COLLATE utf8mb4_general_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vacante ADD idioma_id VARCHAR(3) DEFAULT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DEDC0611 FOREIGN KEY (idioma_id) REFERENCES idioma (id)');
        $this->addSql('CREATE INDEX IDX_B8B6B464DEDC0611 ON vacante (idioma_id)');
    }
}
