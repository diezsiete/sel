<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191210192523 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_hv (vacante_id INT NOT NULL, hv_id INT NOT NULL, INDEX IDX_61969B788B34DB71 (vacante_id), INDEX IDX_61969B78B83428F3 (hv_id), PRIMARY KEY(vacante_id, hv_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante_hv ADD CONSTRAINT FK_61969B788B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_hv ADD CONSTRAINT FK_61969B78B83428F3 FOREIGN KEY (hv_id) REFERENCES hv (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE vacante_usuario');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_usuario (vacante_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_3A92276F8B34DB71 (vacante_id), INDEX IDX_3A92276FDB38439E (usuario_id), PRIMARY KEY(vacante_id, usuario_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276F8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE vacante_hv');
    }
}
