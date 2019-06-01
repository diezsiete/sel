<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601082350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE licencia_conduccion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(12) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profesion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_licencia_conduccion (vacante_id INT NOT NULL, licencia_conduccion_id INT NOT NULL, INDEX IDX_EE67F3E38B34DB71 (vacante_id), INDEX IDX_EE67F3E3A8764065 (licencia_conduccion_id), PRIMARY KEY(vacante_id, licencia_conduccion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_profesion (vacante_id INT NOT NULL, profesion_id INT NOT NULL, INDEX IDX_FFA6E76B8B34DB71 (vacante_id), INDEX IDX_FFA6E76BC5AF4D0F (profesion_id), PRIMARY KEY(vacante_id, profesion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_usuario (vacante_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_3A92276F8B34DB71 (vacante_id), INDEX IDX_3A92276FDB38439E (usuario_id), PRIMARY KEY(vacante_id, usuario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vacante_red_social (id INT AUTO_INCREMENT NOT NULL, vacante_id INT NOT NULL, nombre VARCHAR(20) NOT NULL, id_post VARCHAR(255) DEFAULT NULL, INDEX IDX_7C6BC4878B34DB71 (vacante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E38B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_licencia_conduccion ADD CONSTRAINT FK_EE67F3E3A8764065 FOREIGN KEY (licencia_conduccion_id) REFERENCES licencia_conduccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76B8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_profesion ADD CONSTRAINT FK_FFA6E76BC5AF4D0F FOREIGN KEY (profesion_id) REFERENCES profesion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276F8B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_usuario ADD CONSTRAINT FK_3A92276FDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vacante_red_social ADD CONSTRAINT FK_7C6BC4878B34DB71 FOREIGN KEY (vacante_id) REFERENCES vacante (id)');
        $this->addSql('ALTER TABLE vacante DROP facebook, DROP twitter');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE vacante_licencia_conduccion DROP FOREIGN KEY FK_EE67F3E3A8764065');
        $this->addSql('ALTER TABLE vacante_profesion DROP FOREIGN KEY FK_FFA6E76BC5AF4D0F');
        $this->addSql('DROP TABLE licencia_conduccion');
        $this->addSql('DROP TABLE profesion');
        $this->addSql('DROP TABLE vacante_licencia_conduccion');
        $this->addSql('DROP TABLE vacante_profesion');
        $this->addSql('DROP TABLE vacante_usuario');
        $this->addSql('DROP TABLE vacante_red_social');
        $this->addSql('ALTER TABLE vacante ADD facebook VARCHAR(200) DEFAULT NULL COLLATE utf8mb4_general_ci, ADD twitter VARCHAR(140) DEFAULT NULL COLLATE utf8mb4_general_ci');
    }
}
