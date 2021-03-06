<?php

namespace App\Command\Migration;

use App\Constant\HvConstant;
use App\Entity\Main\Ciudad;
use App\Entity\Main\Dpto;
use App\Entity\Hv\Hv;
use App\Entity\Main\Pais;
use App\Entity\Main\Usuario;
use DateTime;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;


class MigrationHvCommand extends MigrationCommand
{
    protected static $defaultName = 'sel:migration:hv';
    /**
     * @var string
     */
    private $migrationDatabaseSeAspiranteUrl;
    /**
     * @var string
     */
    private $migrationDatabaseSeUrl;

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de hv')
            ->addOption('uid', null, InputOption::VALUE_OPTIONAL,
                'Importar solo uno por id de usuario')
            ->addOption('import_usuario', 'i', InputOption::VALUE_NONE,
                'Si activado, si el usuario no existe lo importa');
    }

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ManagerRegistry $managerRegistry, $migrationDatabaseSeUrl, $migrationDatabaseSeAspiranteUrl)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->migrationDatabaseSeUrl = $migrationDatabaseSeUrl;
        $this->migrationDatabaseSeAspiranteUrl = $migrationDatabaseSeAspiranteUrl;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uid = $input->getOption('uid');

        $seDatabase = $this->getDatabaseNameFromUrl($this->migrationDatabaseSeUrl);
        $aspiranteDatabase = $this->getDatabaseNameFromUrl($this->migrationDatabaseSeAspiranteUrl);

        $sql = "SELECT hv.*, u.nacimiento "
             . "FROM $aspiranteDatabase.hv hv "
             . "JOIN $seDatabase.usuario u ON hv.usuario_id = u.id ";

        if($uid) {
            $sql .= "WHERE u.id = " . $uid;
        }

        $sql = $this->addLimitToSql($sql);

        $count = $this->countSql($sql, self::CONNECTION_SE_ASPIRANTE);
        $this->initProgressBar($count);

        while ($row = $this->fetch($sql, self::CONNECTION_SE_ASPIRANTE)) {
            $usuario = $this->getUsuarioByIdOld($row['usuario_id']);
            if($usuario) {
                $hv = (new Hv())->setUsuario($usuario);

                $this->setLocation('nac', $hv, $row);
                $this->setLocation('ident', $hv, $row);
                $this->setLocation('resi', $hv, $row);

                $hv->setGenero($row['genero_id'])
                    ->setEstadoCivil($row['estado_civil_id'])
                    ->setBarrio($row['barrio'])
                    ->setDireccion($row['direccion'])
                    ->setTelefono(strlen($row['telefono']) > 17 ? null : $row['telefono'])
                    ->setCelular(strlen($row['celular']) > 17 ? null : $row['celular'])
                    ->setGrupoSanguineo($row['grupo_sanguineo'] ? $row['grupo_sanguineo'] : HvConstant::GRUPO_SANGUINEO["A"])
                    ->setFactorRh($row['factor_rh'] ? $row['factor_rh'] : HvConstant::FACTOR_RH["+"])
                    ->setNacionalidad($row['nacionalidad'] ? $row['nacionalidad'] : HvConstant::NACIONALIDAD[1])
                    ->setEmailAlt($row['email_alt'])
                    ->setAspiracionSueldo($row['aspiracion_sueldo'])
                    ->setEstatura($row['estatura'])
                    ->setPeso($row['peso'])
                    ->setPersonasCargo($row['personas_cargo'])
                    ->setIdentificacionTipo($row['ident_tipo_id']);
                
                $nacimiento = $row['nacimiento'];
                if($nacimiento){
                    $hv->setNacimiento(DateTime::createFromFormat('Y-m-d', $nacimiento));
                }
                $hv
                    ->setLmilitarClase($row['lmilitar_clase'])
                    ->setLmilitarNumero($row['lmilitar_numero'])
                    ->setLmilitarDistrito($row['lmilitar_distrito'])
                    ->setPresupuestoMensual($row['presupuesto_mensual'])
                    ->setDeudas($row['deudas'] == 1)
                    ->setDeudasConcepto($row['deudas_concepto'])
                    ->setNivelAcademico($row['nivel_academico_id']);

                $this->selPersist($hv);
            }
        }
    }

    public function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Hv::class);
    }

    /**
     * @return Pais|object|null
     */
    protected function getPais($row, $key)
    {
        $nPaisId = $row[$key . "_pais_id"];
        return $this->getDefaultManager()->getRepository(Pais::class)->findOneBy(['nId' => $nPaisId]);
    }

    /**
     * @return Dpto|object|null
     */
    protected function getDpto($row, $key)
    {
        $nPaisId = $row[$key . "_pais_id"];
        $nDptoId = $row[$key . "_dpto_id"];
        return $this->getDefaultManager()->getRepository(Dpto::class)->findOneBy([
            'nPaisId' => $nPaisId, 'nId' => $nDptoId]);
    }

    /**
     * @return Ciudad|object|null
     */
    protected function getCiudad($row, $key)
    {
        $nPaisId = $row[$key . "_pais_id"];
        $nDptoId = $row[$key . "_dpto_id"];
        $nCiudadId = $row[$key . "_ciudad_id"];

        return $this->getDefaultManager()->getRepository(Ciudad::class)->findOneBy([
            'nId' => $nCiudadId,
            'nPaisId' => $nPaisId,
            'nDptoId' => $nDptoId
        ]);
    }

    protected function setPais($key, $hv, $row)
    {
        $method = "set" . ucfirst($key) . "Pais";
        $hv->$method($this->getPais($row, $key));
    }

    protected function setDpto($key, $hv, $row)
    {
        $method = "set" . ucfirst($key) . "Dpto";
        if($location = $this->getDpto($row, $key)) {
            $hv->$method($location);
        }
    }

    protected function setCiudad($key, $hv, $row)
    {
        $method = "set" . ucfirst($key) . "Ciudad";
        if($location = $this->getCiudad($row, $key)) {
            $hv->$method($location);
        }
    }

    protected function setLocation($key, $hv, $row)
    {
        $this->setPais($key, $hv, $row);
        $this->setDpto($key, $hv, $row);
        $this->setCiudad($key, $hv, $row);
    }


    /**
     * @param $idOld
     * @return \App\Entity\Main\Usuario|object|null
     * @throws \Exception
     */
    protected function getUsuarioByIdOld($idOld, $errorMessage = null)
    {
        $importUsuario = $this->input->getOption('import_usuario');
        $usuario = parent::getUsuarioByIdOld($idOld, $importUsuario ? false : null);

        if(!$usuario && $importUsuario) {
            $this->io->writeln($idOld . " not found");
            $command = $this->getApplication()->find('sel:migration:usuario');
            $arguments = [
                'command' => 'sel:migration:usuario',
                '--id'    => $idOld,
            ];
            $commandInput = new ArrayInput($arguments);
            $code = $command->run($commandInput, new NullOutput());
            $this->io->writeln("sel:migration:usuario -> " . $code);

            $usuario = parent::getUsuarioByIdOld($idOld);
        }
        return $usuario;
    }

    private function getDatabaseNameFromUrl($url)
    {
        preg_match('/\/(\w+)$/', $url, $matches);
        return $matches[1];
    }
}
