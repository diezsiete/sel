<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612211450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pais CHANGE n_id n_id VARCHAR(7) DEFAULT NULL');
        $this->addSql('ALTER TABLE dpto CHANGE n_id n_id VARCHAR(7) DEFAULT NULL, CHANGE n_pais_id n_pais_id VARCHAR(7) DEFAULT NULL');
        $this->addSql('ALTER TABLE ciudad CHANGE n_id n_id VARCHAR(7) DEFAULT NULL, CHANGE n_pais_id n_pais_id VARCHAR(7) DEFAULT NULL, CHANGE n_dpto_id n_dpto_id VARCHAR(7) DEFAULT NULL');
        $this->addSql('ALTER TABLE vacante_red_social DROP FOREIGN KEY FK_7C6BC4878B34DB71');
        $this->addSql('ALTER TABLE vacante_red_social ADD CONSTRAINT FK_7C6BC4878B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ciudad CHANGE n_id n_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, CHANGE n_pais_id n_pais_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, CHANGE n_dpto_id n_dpto_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE dpto CHANGE n_id n_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci, CHANGE n_pais_id n_pais_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE pais CHANGE n_id n_id VARCHAR(7) NOT NULL COLLATE utf8mb4_general_ci');
        $this->addSql('ALTER TABLE vacante_red_social DROP FOREIGN KEY FK_7C6BC4878B34DB71');
        $this->addSql('ALTER TABLE vacante_red_social ADD CONSTRAINT FK_7C6BC4878B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
    }
}
