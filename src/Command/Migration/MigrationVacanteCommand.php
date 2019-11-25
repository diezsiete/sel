<?php

namespace App\Command\Migration;

use App\Command\Helpers\SelCommandTrait;
use App\Entity\Cargo;
use App\Entity\Ciudad;
use App\Entity\LicenciaConduccion;
use App\Entity\Profesion;
use App\Entity\Vacante;
use App\Entity\VacanteArea;
use App\Entity\VacanteRedSocial;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MigrationVacanteCommand extends MigrationCommand
{
    use SelCommandTrait;

    protected static $defaultName = 'sel:migration:vacante';

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de vacante');
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->deleteTable(VacanteRedSocial::class);
        $this->deleteTable(Vacante::class);
        $this->truncateTable(Vacante::class);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = "SELECT * "
             . "FROM `vacante` v ";

        $sql = $this->addLimitToSql($sql);

        $this->initProgressBar($this->countSql($sql));

        while ($row = $this->fetch($sql)) {
            $usuario = $this->getUsuarioByIdOld($row['user_id'], "Vacante no creada: usuario con idOld '%id%' no encontrado");
            if($usuario) {
                $vacante = (new Vacante())
                    ->setUsuario($usuario)
                    ->setTitulo($row['titulo'])
                    ->setDescripcion($row['descripcion'])
                    ->setRequisitos($row['requisitos'])
                    ->setNivel($row['nivel_id'])
                    ->setSubnivel($row['subnivel_id'])
                    ->setContratoTipo($row['contrato_tipo_id'])
                    ->setIntensidadHoraria($row['intensidad_horaria_id'])
                    ->setVacantesCantidad($row['vacantes_cantidad'])
                    ->setSalarioRango($row['salario_rango_id'])
                    ->setSalarioPublicar($row['salario_publicar'] == 1)
                    ->setSalarioNeto($row['salario_neto'])
                    ->setSalarioAdicion($row['salario_adicion'])
                    ->setSalarioAdicionConcepto($row['salario_adicion_concepto'])
                    ->setNivelAcademicoCurso($row['nivel_academico_curso'] == 1)
                    ->setEmpresa(strtoupper($row['empresa']) == 'PTA' ? 1 : 2)
                    ->setVigencia($row['vigencia_id'])
                    ->setCreatedAt(\DateTime::createFromFormat('Y-m-d', $row['date']));

                $this
                    ->setIdioma($vacante, $row['idioma_id'], $row['idioma_porcentaje'])
                    ->setNivelAcademico($vacante, $row['nivel_academico_id'])
                    ->setExperiencia($vacante, $row['experiencia_id'])
                    ->setGenero($vacante, $row['genero']);

                $this
                    ->addCiudad($vacante, $row['id'])
                    ->addEntity($vacante, $row['id'], VacanteArea::class, 'vacante_area', 'area_id', function(Vacante $vacante, $area){
                        $vacante->addArea($area);
                    })
                    ->addEntity($vacante, $row['id'], Cargo::class, 'vacante_cargo', 'cargo_id', function(Vacante $vacante, $cargo){
                        $vacante->addCargo($cargo);
                    })
                    ->addEntity($vacante, $row['id'], Profesion::class, 'vacante_profesion', 'profesion_id', function(Vacante $vacante, $profesion){
                        $vacante->addProfesion($profesion);
                    })
                    ->addEntity($vacante, $row['id'], LicenciaConduccion::class, 'vacante_licencia_conduccion', 'licencia_conduccion_id', function(Vacante $vacante, $licenciaConduccion){
                        $vacante->addLicenciaConduccion($licenciaConduccion);
                    })
                    ->addEntity($vacante, $row['id'], null, 'vacante_red_social', null, function(Vacante $vacante, $row){
                       $vacanteRedSocial = (new VacanteRedSocial())
                           ->setNombre($row['red_social_id'] == 1 ? 'facebook' : 'twitter')
                           ->setIdPost($row['id_post']);
                       $vacante->addRedesSocial($vacanteRedSocial);
                    })
                    ->addAplicante($vacante, $row['id']);

                $this->selPersist($vacante);
            }
        }
    }

    protected function setIdioma(Vacante $vacante, $idiomaId, $idiomaPorcentaje)
    {
        if($idiomaId) {
            $mapper = [
                1 => '01', //ingles
                2 => '03', //frances,
                3 => '06', //chino,
                4 => '04', //italiano,
                5 => '05', //aleman
                6 => '10', //portuges
            ];
            $vacante->setIdiomaCodigo($mapper[$idiomaId]);
            
            $idiomaDestreza = 2;
            if($idiomaPorcentaje >= 3) {
                $idiomaDestreza = 3;
            }
            if($idiomaPorcentaje >= 6) {
                $idiomaDestreza = 4;
            }
            if($idiomaPorcentaje >= 9) {
                $idiomaDestreza = 5;
            }
            $vacante->setIdiomaDestreza($idiomaDestreza);
        }
        return $this;
    }

    protected function setNivelAcademico(Vacante $vacante, $nivelAcademicoId)
    {
        $mapper = [
            1 => '01', 2 => '02', 3 => '03', 4 => '04', 5 => '05', 6 => '07', 7 => '08', 8 => '09', 9 => '10', 10 => '11', 11 => '12'
        ];
        $vacante->setNivelAcademico($mapper[$nivelAcademicoId]);
        return $this;
    }

    protected function setExperiencia(Vacante $vacante, $experienciaId)
    {
        $mapper = [
            1 => 0, 2 => 2, 3 => 3, 4 => 4, 5 => 4, 6 => 5, 7 => 5, 8 => 5, 9 => 5
        ];
        if($experienciaId > 9) {
            $mapper[$experienciaId] = 6;
        }
        $vacante->setExperiencia($mapper[$experienciaId]);
        return $this;
    }

    protected function setGenero(Vacante $vacante, $genero)
    {
        if($genero) {
            $vacante->setGenero($genero == 1 ? 2 : 1);
        }
        return $this;
    }

    protected function addCiudad(Vacante $vacante, $vacanteId)
    {
        $sql = "SELECT UPPER(c.name) ciudad, UPPER(d.name) dpto 
                FROM vacante_ciudad vc
                JOIN ciudad c ON vc.ciudad_id = c.id
                JOIN departamento d ON c.departamento_id = d.id
                WHERE vc.vacante_id = " . $vacanteId;

        $stmt = $this->getConnection(self::CONNECTION_SE_VACANTES)->query($sql);
        $rows = $stmt->fetchAll();

        foreach($rows as $row) {
            $ciudad = $this->em->getRepository(Ciudad::class)->findByNombre($row['ciudad'], $row['dpto']);
            if(!$ciudad) {
                $ciudad = $this->em->getRepository(Ciudad::class)->findByNombre($row['ciudad']);
                if(!$ciudad) {
                    throw new \Exception("Ciudad '" . $row['ciudad'] . "' con dpto '" . $row['dpto'] . "' no encontrada");
                }
            }
            if(count($ciudad) > 1) {
                throw new \Exception("Ciudad '" . $row['ciudad'] . "' con dpto '". $row['dpto']. "' se encontraron varias opciones");
            }
            $vacante->addCiudad($ciudad[0]);
        }
        return $this;
    }

    protected function addEntity(Vacante $vacante, $vacanteId, $entityClass, $tableRelation, $tableIdName, $assignCallback)
    {
        $sql = "SELECT * FROM $tableRelation WHERE vacante_id = " . $vacanteId;
        $stmt = $this->getConnection(self::CONNECTION_SE_VACANTES)->query($sql);
        $rows = $stmt->fetchAll();
        foreach($rows as $row) {
            if($entityClass !== null) {
                $entity = $this->em->getRepository($entityClass)->find($row[$tableIdName]);
                $assignCallback($vacante, $entity);
            } else {
                $assignCallback($vacante, $row);
            }

        }
        return $this;
    }

    protected function addAplicante(Vacante $vacante, $vacanteId)
    {
        $sql = "SELECT * FROM vacante_usuario WHERE vacante_id = " . $vacanteId;
        $stmt = $this->getConnection(self::CONNECTION_SE_VACANTES)->query($sql);
        $rows = $stmt->fetchAll();
        foreach($rows as $row) {
            if($usuario = $this->getUsuarioByIdOld($row['usuario_id'], "Add aplicante : usuario con idOld '%id%' no encontrado")) {
                $vacante->addAplicante($usuario);
            }
        }
        return $this;
    }


    protected function countSql($sql, $connectionName = self::CONNECTION_SE)
    {
        $connectionName = self::CONNECTION_SE_VACANTES;
        return parent::countSql($sql, $connectionName);
    }

    protected function fetch($sql, $connectionName = self::CONNECTION_SE)
    {
        $connectionName = self::CONNECTION_SE_VACANTES;
        return parent::fetch($sql, $connectionName);
    }


}
