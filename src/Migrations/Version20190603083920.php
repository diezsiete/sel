<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190603083920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE idioma (id INT AUTO_INCREMENT NOT NULL, hv_id INT NOT NULL, idioma_codigo VARCHAR(3) NOT NULL, destreza VARCHAR(2) NOT NULL, INDEX IDX_1DC85E0CB83428F3 (hv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE idioma ADD CONSTRAINT FK_1DC85E0CB83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id)');
        $this->addSql('ALTER TABLE vacante ADD idioma_codigo VARCHAR(3) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE idioma');
        $this->addSql('ALTER TABLE vacante DROP idioma_codigo');
    }
}
