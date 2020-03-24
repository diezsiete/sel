<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Service\Configuracion\Configuracion;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317041947 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $configuracion = $this->container->get(Configuracion::class);

        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE genero (id INT NOT NULL, nombre VARCHAR(9) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO genero (id, nombre) VALUES (1, \'FEMENINO\'), (2, \'MASCULINO\'), (3, \'OTRO\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759A000883A FOREIGN KEY (genero) REFERENCES genero (id)');
        $this->addSql('CREATE INDEX IDX_559B2759A000883A ON hv (genero)');

        $this->addSql('CREATE TABLE estado_civil (id SMALLINT NOT NULL, nombre VARCHAR(11) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO estado_civil (id, nombre) VALUES (1, \'SOLTERO\'), (2, \'CASADO\'), (3, \'DIVORCIADO\'), (4, \'VIUDO\'), (5, \'UNION LIBRE\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759F4222D84 FOREIGN KEY (estado_civil) REFERENCES estado_civil (id)');
        $this->addSql('CREATE INDEX IDX_559B2759F4222D84 ON hv (estado_civil)');

        $this->addSql('CREATE TABLE grupo_sanguineo (id VARCHAR(3) NOT NULL, nombre VARCHAR(2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO grupo_sanguineo (id, nombre) VALUES  (\'A\', \'A\'), (\'B\',  \'B\'), (\'AB\', \'AB\'), (\'O\', \'O\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759E68A4AF6 FOREIGN KEY (grupo_sanguineo) REFERENCES grupo_sanguineo (id)');
        $this->addSql('CREATE INDEX IDX_559B2759E68A4AF6 ON hv (grupo_sanguineo)');

        $this->addSql('CREATE TABLE factor_rh (id VARCHAR(1) NOT NULL, nombre VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO factor_rh (id, nombre) VALUES  (\'+\', \'+\'), (\'-\',  \'-\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27591C084612 FOREIGN KEY (factor_rh) REFERENCES factor_rh (id)');
        $this->addSql('CREATE INDEX IDX_559B27591C084612 ON hv (factor_rh)');

        $this->addSql('CREATE TABLE nacionalidad (id SMALLINT NOT NULL, nombre VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO nacionalidad (id, nombre) VALUES (1, \'Colombiano\'), (2, \'Doble\'), (3, \'Extranjero\');');
        $this->addSql('UPDATE `hv` SET nacionalidad = 1 WHERE nacionalidad = 0');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B2759931D5FC3 FOREIGN KEY (nacionalidad) REFERENCES nacionalidad (id)');
        $this->addSql('CREATE INDEX IDX_559B2759931D5FC3 ON hv (nacionalidad)');

        $this->addSql('CREATE TABLE identificacion_tipo (id VARCHAR(2) NOT NULL, nombre VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO identificacion_tipo (id, nombre) VALUES  
            (\'01\', \'Cédula de Ciudadania\'),
            (\'02\', \'Cédula de Extranjería\'),
            (\'03\', \'Tarjeta de Identidad\'),
            (\'04\', \'Número Unico de Identificación Personal\'),
            (\'05\', \'Registro Civil\'),
            (\'06\', \'Pasaporte\'),
            (\'10\', \'Número de Identificación Tributaria\'),
            (\'21\', \'Tarjeta de Extranjería\'),
            (\'22\', \'Tipo documento Extranjero\'),
            (\'23\', \'Documento definido información exogena\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27595119DC2B FOREIGN KEY (identificacion_tipo) REFERENCES identificacion_tipo (id)');
        $this->addSql('CREATE INDEX IDX_559B27595119DC2B ON hv (identificacion_tipo)');

        $this->addSql('CREATE TABLE nivel_academico (id VARCHAR(3) NOT NULL, nombre VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO nivel_academico (id, nombre) VALUES  
            (\'01\',  \'PREESCOLAR\'),
            (\'02\',  \'BASICA PRIMARIA\'),
            (\'03\',  \'BASICA SECUNDARIA\'),
            (\'04\',  \'MEDIA ACADEMICA O CLASICA\'),
            (\'05\',  \'MEDIA TECNICAS\'),
            (\'06\',  \'NORMALISTA\'),
            (\'07\',  \'TECNICA PROFESIONAL\'),
            (\'08\',  \'TECNOLOGICO\'),
            (\'09\',  \'PROFESIONAL\'),
            (\'10\',  \'ESPECIALISTA\'),
            (\'11\',  \'MAESTRIA\'),
            (\'12\',  \'DOCTORADO\'),
            (\'13\',  \'NINGUNO\');');
        $this->addSql('ALTER TABLE hv ADD CONSTRAINT FK_559B27595B91420 FOREIGN KEY (nivel_academico) REFERENCES nivel_academico (id)');
        $this->addSql('CREATE INDEX IDX_559B27595B91420 ON hv (nivel_academico)');

        $this->addSql('CREATE TABLE experiencia_duracion (id SMALLINT NOT NULL, nombre VARCHAR(21) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO experiencia_duracion (id, nombre) VALUES  
            (0, \'NO APLICA\'),
            (2, \'DE 0 A 1 AÑO\'),
            (3, \'DE 1 A 2 AÑOS\'),
            (4, \'DE 2 a 3 AÑOS\'),
            (5, \'DE 4 A 7 AÑOS\'),
            (6, \'De 8 AÑOS EN ADELANTE\');');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E30344B91EFE6 FOREIGN KEY (duracion) REFERENCES experiencia_duracion (id)');
        $this->addSql('CREATE INDEX IDX_DD0E30344B91EFE6 ON experiencia (duracion)');

        $this->addSql('CREATE TABLE referencia_tipo (id SMALLINT NOT NULL, nombre VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE = InnoDB');

        if($configuracion->getEmpresa() === 'SERVILABOR') {
            $this->addSql('INSERT INTO referencia_tipo (id, nombre) VALUES (7, \'PERSONAL\'), (8, \'FAMILIAR\'), (9, \'LABORAL\');');
        } else {
            $this->addSql('INSERT INTO referencia_tipo (id, nombre) VALUES (1, \'PERSONAL\'), (2, \'FAMILIAR\'), (3, \'LABORAL\');');
        }
        $this->addSql('ALTER TABLE referencia ADD CONSTRAINT FK_C01213D8702D1D47 FOREIGN KEY (tipo) REFERENCES referencia_tipo (id)');
        $this->addSql('CREATE INDEX IDX_C01213D8702D1D47 ON referencia (tipo)');

        $this->addSql('ALTER TABLE hv ADD t3rs TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hv DROP t3rs');

        $this->addSql('ALTER TABLE referencia DROP FOREIGN KEY FK_C01213D8702D1D47');
        $this->addSql('DROP TABLE referencia_tipo');
        $this->addSql('DROP INDEX IDX_C01213D8702D1D47 ON referencia');

        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E30344B91EFE6');
        $this->addSql('DROP TABLE experiencia_duracion');
        $this->addSql('DROP INDEX IDX_DD0E30344B91EFE6 ON experiencia');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B27595B91420');
        $this->addSql('DROP TABLE nivel_academico');
        $this->addSql('DROP INDEX IDX_559B27595B91420 ON hv');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B27595119DC2B');
        $this->addSql('DROP TABLE identificacion_tipo');
        $this->addSql('DROP INDEX IDX_559B27595119DC2B ON hv');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759931D5FC3');
        $this->addSql('DROP TABLE nacionalidad');
        $this->addSql('DROP INDEX IDX_559B2759931D5FC3 ON hv');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B27591C084612');
        $this->addSql('DROP TABLE factor_rh');
        $this->addSql('DROP INDEX IDX_559B27591C084612 ON hv');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759E68A4AF6');
        $this->addSql('DROP TABLE grupo_sanguineo');
        $this->addSql('DROP INDEX IDX_559B2759E68A4AF6 ON hv');

        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759A000883A');
        $this->addSql('DROP TABLE genero');
        $this->addSql('DROP INDEX IDX_559B2759A000883A ON hv');
        $this->addSql('ALTER TABLE hv DROP FOREIGN KEY FK_559B2759F4222D84');
        $this->addSql('DROP TABLE estado_civil');
        $this->addSql('DROP INDEX IDX_559B2759F4222D84 ON hv');
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
