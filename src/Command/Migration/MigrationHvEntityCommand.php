<?php

namespace App\Command\Migration;

use App\Entity\Area;
use App\Entity\Ciudad;
use App\Entity\Dpto;
use App\Entity\Estudio;
use App\Entity\EstudioCodigo;
use App\Entity\EstudioInstituto;
use App\Entity\Experiencia;
use App\Entity\Familiar;
use App\Entity\Hv;
use App\Entity\Idioma;
use App\Entity\Pais;
use App\Entity\RedSocial;
use App\Entity\Referencia;
use App\Entity\Vivienda;
use App\Repository\HvRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationHvEntityCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:hv-entity';

    private $entites = ['estudio', 'experiencia', 'familiar', 'red_social', 'referencia', 'vivienda', 'idioma'];
    /**
     * @var HvRepository
     */
    protected $hvRepository = null;

    protected function configure()
    {
        $this->setDescription('Migrar entidades relacionadas a hv')
            ->addArgument('entity', InputArgument::REQUIRED, 'la entidad a importar');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $entity = $input->getArgument('entity');
        $entities = $entity ? [$entity] : $this->entites;
        $count = 0;
        foreach($entities as $entity) {
            $sql = "SELECT * FROM " . $entity;
            $count += $this->countSql($sql);
        }
        $this->initProgressBar($count);

        foreach($entities as $entity) {
            $sql = "SELECT * FROM " . $entity;
            while ($row = $this->seFetch($sql)) {
                $hv = $this->getHvByUsuarioIdOld($row['usuario_id']);
                if ($hv) {
                    switch ($entity) {
                        case 'estudio':
                            $object = $this->addEstudio($row, $hv);
                            break;
                        case 'experiencia':
                            $object = $this->addExperiencia($row, $hv);
                            break;
                        case 'familiar':
                            $object = $this->addFamiliar($row, $hv);
                            break;
                        case 'red_social':
                            $object = $this->addRedSocial($row, $hv);
                            break;
                        case 'referencia':
                            $object = $this->addReferencia($row, $hv);
                            break;
                        case 'vivienda':
                            $object = $this->addVivienda($row, $hv);
                            break;
                        case 'idioma':
                            $object = $this->addIdioma($row, $hv);
                            break;
                    }
                    $this->selPersist($object);
                }
            }
        }
    }

    private function getHvByUsuarioIdOld($usuario_id)
    {
        $hv = null;
        $usuario = $this->getUsuarioByIdOld($usuario_id);
        if ($usuario) {
            if (!$this->hvRepository) {
                $this->hvRepository = $this->getDefaultManager()->getRepository(Hv::class);
            }
            $hv = $this->hvRepository->findOneBy(['usuario' => $usuario]);
            if (!$hv) {
                $this->io->error("[".$usuario->getId()."] ".$usuario->getNombreCompleto(true) . " : HV no existe");
            }
        }
        return $hv;
    }

    private function addEstudio($row, $hv)
    {
        $estudio = new Estudio();

        $codigo = $this->getDefaultManager()->getRepository(EstudioCodigo::class)->find($row['estudio_codigo_id']);
        $instituto = $this->getDefaultManager()->getRepository(EstudioInstituto::class)->find($row['estudio_instituto_id']);
        $estudio
            ->setCodigo($codigo)
            ->setNombre($row['nombre'])
            ->setInstituto($instituto)
            ->setAnoEstudio($row['ano_estudio'])
            ->setHorasEstudio($row['horas_estudio'])
            ->setSemestresAprobados($row['semestres_aprobados'])
            ->setNumeroTarjeta($row['numero_tarjeta'])
            ->setHv($hv);
        if ($row['fin']) {
            $estudio->setFin(\DateTime::createFromFormat('Y-m-d', $row['fin']));
        }
        if ($row['institucion_nombre_alt']) {
            $estudio->setInstitutoNombreAlt($row['institucion_nombre_alt']);
        }
        if ($row['graduado'] !== null) {
            $estudio->setGraduado($row['graduado'] == 1);
        }

        if ($row['cancelo'] !== null) {
            $estudio->setCancelo($row['cancelo'] == 1);
        }
        return $estudio;
    }

    private function addExperiencia($row, $hv)
    {
        $area = $this->getDefaultManager()->getRepository(Area::class)->find($row['experiencia_area_id']);
        $experiencia = (new Experiencia())
            ->setHv($hv)
            ->setEmpresa($row['empresa'])
            ->setCargo($row['cargo'])
            ->setArea($area)
            ->setDescripcion($row['descripcion'])
            ->setDuracion($row['experiencia_duracion_id'])
            ->setLogrosObtenidos($row['logros_obtenidos'])
            ->setMotivoRetiro($row['motivo_retiro'])
            ->setJefeInmediato($row['jefe_inmediato'])
            ->setSalarioBasico($row['salario_basico'])
            ->setTelefonoJefe($row['telefono_jefe']);
        if ($row['fecha_ingreso']) {
            $experiencia->setFechaIngreso(\DateTime::createFromFormat('Y-m-d', $row['fecha_ingreso']));
        }
        if ($row['fecha_retiro']) {
            $experiencia->setFechaIngreso(\DateTime::createFromFormat('Y-m-d', $row['fecha_retiro']));
        }
        return $experiencia;
    }

    private function addFamiliar($row, $hv)
    {
        $familiar = (new Familiar())
            ->setHv($hv)
            ->setPrimerApellido($row['primer_apellido'])
            ->setSegundoApellido($row['segundo_apellido'])
            ->setNombre($row['nombre'])
            ->setParentesco($row['parentesco'])
            ->setOcupacion($row['ocupacion'])
            ->setParentesco($row['parentesco'])
            ->setNivelAcademico($row['nivel_academico_id'])
            ->setGenero($row['genero_id'])
            ->setEstadoCivil($row['estado_civil_id'])
            ->setIdentificacion($row['ident'])
            ->setIdentificacionTipo($row['ident_tipo_id']);
        if ($row['nacimiento']) {
            $familiar->setNacimiento(\DateTime::createFromFormat('Y-m-d', $row['nacimiento']));
        }
        return $familiar;
    }


    private function addRedSocial($row, $hv)
    {
        $redSocial = (new RedSocial())
            ->setHv($hv)
            ->setTipo($row['red_social_codigo_id'])
            ->setCuenta($row['cuenta']);
        return $redSocial;
    }

    private function addReferencia($row, $hv)
    {
        $referencia = (new Referencia())
            ->setHv($hv)
            ->setTipo($row['referencia_tipo_id'])
            ->setNombre($row['nombre'])
            ->setOcupacion($row['ocupacion'])
            ->setParentesco($row['parentesco'])
            ->setCelular($row['celular'])
            ->setEntidad($row['entidad'])
            ->setTelefono($row['telefono']);
        return $referencia;
    }

    private function addVivienda($row, $hv)
    {
        $vivienda = (new Vivienda())
            ->setHv($hv)
            ->setDireccion($row['direccion'])
            ->setEstrato($row['estrato'])
            ->setTipoVivienda($row['tipo_vivienda'])
            ->setTenedor($row['tenedor']);
        if ($row['vivenda_actual']) {
            $vivienda->setViviendaActual($row['vivienda_actual'] == 1);
        }
        
        $nPaisId = $row["pais_id"];
        $nDptoId = $row["dpto_id"];
        $nCiudadId = $row["ciudad_id"];

        $pais = $this->getDefaultManager()->getRepository(Pais::class)->findOneBy(['nId' => $nPaisId]);
        $vivienda->setPais($pais);
        if($nDptoId) {
            $dpto = $this->getDefaultManager()->getRepository(Dpto::class)
                ->findOneBy(['nPaisId' => $nPaisId, 'nId' => $nDptoId]);
            if ($dpto) {
                $vivienda->setDpto($dpto);
            }
        }
        if($nDptoId && $nCiudadId) {
            $ciudad = $this->getDefaultManager()->getRepository(Ciudad::class)->findOneBy([
                'nId' => $nCiudadId,
                'nPaisId' => $nPaisId,
                'nDptoId' => $nDptoId
            ]);
            if ($ciudad) {
                $vivienda->setCiudad($ciudad);
            }
        }
        return $vivienda;
    }

    private function addIdioma($row, $hv)
    {
        $idioma = (new Idioma())
            ->setHv($hv)
            ->setIdiomaCodigo($row['idioma_codigo'])
            ->setDestreza($row['nivel_destreza']);
        return $idioma;
    }
}
