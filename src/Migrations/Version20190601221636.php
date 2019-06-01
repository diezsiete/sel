<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601221636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vacante_vacante_area (vacante_id INT NOT NULL, vacante_area_id INT NOT NULL, INDEX IDX_503A2CD68B34DB71 (vacante_id), INDEX IDX_503A2CD6A5F8D7AE (vacante_area_id), PRIMARY KEY(vacante_id, vacante_area_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_area (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante_vacante_area ADD CONSTRAINT FK_503A2CD68B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_vacante_area ADD CONSTRAINT FK_503A2CD6A5F8D7AE FOREIGN KEY (vacante_area_id) REFERENCES vacante_area (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante CHANGE requisitos requisitos LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante_vacante_area DROP FOREIGN KEY FK_503A2CD6A5F8D7AE');
        $this->addSql('DROP TABLE vacante_vacante_area');
        $this->addSql('DROP TABLE vacante_area');
        $this->addSql('ALTER TABLE vacante CHANGE requisitos requisitos LONGTEXT DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}
