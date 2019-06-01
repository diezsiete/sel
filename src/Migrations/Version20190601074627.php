<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601074627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_area (vacante_id INT NOT NULL, area_id VARCHAR(7) NOT NULL, INDEX IDX_99DCE3218B34DB71 (vacante_id), INDEX IDX_99DCE321BD0F409C (area_id), PRIMARY KEY(vacante_id, area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_cargo (vacante_id INT NOT NULL, cargo_id VARCHAR(7) NOT NULL, INDEX IDX_34A0E69B8B34DB71 (vacante_id), INDEX IDX_34A0E69B813AC380 (cargo_id), PRIMARY KEY(vacante_id, cargo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_ciudad (vacante_id INT NOT NULL, ciudad_id VARCHAR(7) NOT NULL, INDEX IDX_CE5640498B34DB71 (vacante_id), INDEX IDX_CE564049E8608214 (ciudad_id), PRIMARY KEY(vacante_id, ciudad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante_area ADD CONSTRAINT FK_99DCE3218B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_area ADD CONSTRAINT FK_99DCE321BD0F409C FOREIGN KEY (area_id) REFERENCES area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_cargo ADD CONSTRAINT FK_34A0E69B813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE5640498B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_ciudad ADD CONSTRAINT FK_CE564049E8608214 FOREIGN KEY (ciudad_id) REFERENCES ciudad (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vacante_area');
        $this->addSql('DROP TABLE vacante_cargo');
        $this->addSql('DROP TABLE vacante_ciudad');
    }
}
