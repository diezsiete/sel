<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200715045540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ocupacion (id SMALLINT NOT NULL, nombre VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO ocupacion (id, nombre) VALUES
            (\'1\', \'EMPLEADO\'),
            (\'2\', \'ESTUDIANTE\'),
            (\'3\', \'HOGAR\'),
            (\'4\', \'DESEMPLEADO\'),
            (\'5\', \'PENSIONADO\'),
            (\'6\', \'INDEPENDIENTE\'),
            (\'7\', \'OTRAS\');');
        $this->addSql('CREATE TABLE parentesco (id VARCHAR(2) NOT NULL, nombre VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO parentesco (id, nombre) VALUES  
            (\'01\', \'NIETO\'),
            (\'02\', \'HIJO\'),
            (\'04\', \'HERMANO\'),
            (\'05\', \'TIO\'),
            (\'06\', \'SOBRINO\'),
            (\'07\', \'PRIMO\'),
            (\'08\', \'CUÑADO\'),
            (\'09\', \'YERNO/NUERA\'),
            (\'10\', \'SUEGRO\'),
            (\'11\', \'CONYUGE\'),
            (\'12\', \'ABUELO\'),                               
            (\'13\', \'PADRE/MADRE\');');
        $this->addSql('ALTER TABLE familiar CHANGE ocupacion ocupacion SMALLINT NOT NULL, CHANGE estado_civil estado_civil SMALLINT NOT NULL, CHANGE nivel_academico nivel_academico VARCHAR(3) NOT NULL, CHANGE identificacion_tipo identificacion_tipo VARCHAR(2) DEFAULT NULL, CHANGE nacimiento nacimiento DATE NOT NULL');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5E9E1E3016 FOREIGN KEY (parentesco) REFERENCES parentesco (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EC6DC246 FOREIGN KEY (ocupacion) REFERENCES ocupacion (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EA000883A FOREIGN KEY (genero) REFERENCES genero (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5EF4222D84 FOREIGN KEY (estado_civil) REFERENCES estado_civil (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5E5119DC2B FOREIGN KEY (identificacion_tipo) REFERENCES identificacion_tipo (id)');
        $this->addSql('ALTER TABLE familiar ADD CONSTRAINT FK_8A34CA5E5B91420 FOREIGN KEY (nivel_academico) REFERENCES nivel_academico (id)');
        $this->addSql('CREATE INDEX IDX_8A34CA5E9E1E3016 ON familiar (parentesco)');
        $this->addSql('CREATE INDEX IDX_8A34CA5EC6DC246 ON familiar (ocupacion)');
        $this->addSql('CREATE INDEX IDX_8A34CA5EA000883A ON familiar (genero)');
        $this->addSql('CREATE INDEX IDX_8A34CA5EF4222D84 ON familiar (estado_civil)');
        $this->addSql('CREATE INDEX IDX_8A34CA5E5119DC2B ON familiar (identificacion_tipo)');
        $this->addSql('CREATE INDEX IDX_8A34CA5E5B91420 ON familiar (nivel_academico)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5EC6DC246');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5E9E1E3016');
        $this->addSql('DROP TABLE ocupacion');
        $this->addSql('DROP TABLE parentesco');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5EA000883A');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5EF4222D84');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5E5119DC2B');
        $this->addSql('ALTER TABLE familiar DROP FOREIGN KEY FK_8A34CA5E5B91420');
        $this->addSql('DROP INDEX IDX_8A34CA5E9E1E3016 ON familiar');
        $this->addSql('DROP INDEX IDX_8A34CA5EC6DC246 ON familiar');
        $this->addSql('DROP INDEX IDX_8A34CA5EA000883A ON familiar');
        $this->addSql('DROP INDEX IDX_8A34CA5EF4222D84 ON familiar');
        $this->addSql('DROP INDEX IDX_8A34CA5E5119DC2B ON familiar');
        $this->addSql('DROP INDEX IDX_8A34CA5E5B91420 ON familiar');
        $this->addSql('ALTER TABLE familiar CHANGE ocupacion ocupacion INT NOT NULL, CHANGE estado_civil estado_civil INT NOT NULL, CHANGE nivel_academico nivel_academico VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE identificacion_tipo identificacion_tipo VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nacimiento nacimiento DATE DEFAULT NULL');
    }
}
