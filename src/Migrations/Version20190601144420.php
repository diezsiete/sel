<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601144420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_ciudad (vacante_id INT NOT NULL, pais_id VARCHAR(7) NOT NULL, dpto_id VARCHAR(7) NOT NULL, ciudad_id VARCHAR(7) NOT NULL, INDEX IDX_CE5640498B34DB71 (vacante_id), INDEX IDX_VC_PAIS_ID (pais_id), INDEX IDX_VC_DPTO_ID (dpto_id), INDEX IDX_CE564049E8608214 (ciudad_id), PRIMARY KEY(vacante_id, pais_id, dpto_id, ciudad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE5640498B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE564049E8608214 FOREIGN KEY (ciudad_id) REFERENCES ciudad (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_VC_PAIS_ID FOREIGN KEY (pais_id) REFERENCES ciudad (pais_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_VC_DPTO_ID FOREIGN KEY (dpto_id) REFERENCES ciudad (dpto_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vacante_ciudad');
    }
}
