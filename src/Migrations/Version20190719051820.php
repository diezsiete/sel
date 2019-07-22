<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190719051820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE autoliquidacion_empleado (id INT AUTO_INCREMENT NOT NULL, empleado_id INT NOT NULL, autoliquidacion_id INT NOT NULL, exito TINYINT(1) NOT NULL, salida VARCHAR(145) DEFAULT NULL, INDEX IDX_2680A346952BE730 (empleado_id), INDEX IDX_2680A3461AF3C28 (autoliquidacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representante (id INT AUTO_INCREMENT NOT NULL, convenio_codigo VARCHAR(45) NOT NULL, usuario_id INT NOT NULL, encargado TINYINT(1) NOT NULL, bcc TINYINT(1) NOT NULL, email VARCHAR(140) DEFAULT NULL, INDEX IDX_DE2D59532717B6D (convenio_codigo), INDEX IDX_DE2D595DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autoliquidacion_empleado ADD CONSTRAINT FK_2680A346952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE autoliquidacion_empleado ADD CONSTRAINT FK_2680A3461AF3C28 FOREIGN KEY (autoliquidacion_id) REFERENCES autoliquidacion (id)');
        $this->addSql('ALTER TABLE representante ADD CONSTRAINT FK_DE2D59532717B6D FOREIGN KEY (convenio_codigo) REFERENCES convenio (codigo)');
        $this->addSql('ALTER TABLE representante ADD CONSTRAINT FK_DE2D595DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE empleado ADD representante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF522FD20D28 FOREIGN KEY (representante_id) REFERENCES representante (id)');
        $this->addSql('CREATE INDEX IDX_D9D9BF522FD20D28 ON empleado (representante_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF522FD20D28');
        $this->addSql('DROP TABLE autoliquidacion_empleado');
        $this->addSql('DROP TABLE representante');
        $this->addSql('DROP INDEX IDX_D9D9BF522FD20D28 ON empleado');
        $this->addSql('ALTER TABLE empleado DROP representante_id');
    }
}
