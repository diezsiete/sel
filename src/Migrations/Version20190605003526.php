<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605003526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usuario ADD id_old INT NOT NULL');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DB38439E');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464C21F5FA8');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usuario DROP id_old');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464DB38439E');
        $this->addSql('ALTER TABLE vacante DROP FOREIGN KEY FK_B8B6B464C21F5FA8');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante ADD CONSTRAINT FK_B8B6B464C21F5FA8 FOREIGN KEY (nivel_academico_id) REFERENCES nivel_academico (id) ON UPDATE CASCADE');
    }
}
