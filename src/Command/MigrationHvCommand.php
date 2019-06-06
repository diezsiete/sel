<?php

namespace App\Command;

use App\Entity\Ciudad;
use App\Entity\Dpto;
use App\Entity\Hv;
use App\Entity\HvAdjunto;
use App\Entity\Pais;
use App\Service\UploaderHelper;
use Doctrine\Common\Persistence\ManagerRegistry;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MigrationHvCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:hv';

    /**
     * @var FilesystemInterface
     */
    private $privateFileSystem;

    /**
     * @var string
     */
    private $projectDir;
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(ManagerRegistry $managerRegistry, FilesystemInterface $privateUploadFileSystem,
                                UploaderHelper $uploaderHelper, string $kernelProjectDir)
    {
        parent::__construct($managerRegistry);
        $this->privateFileSystem = $privateUploadFileSystem;
        $this->projectDir = $kernelProjectDir;
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de hv');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchSize = 20;
        $sql = "SELECT hv.*, u.nacimiento "
             . "FROM `hv` hv "
             . "JOIN `usuario` u ON hv.usuario_id = u.id "
             . "ORDER BY hv.id DESC ";

        $sql = $this->addLimitToSql($sql);

        $se = $this->getSeConnection();

        $progressBar = $this->getProgressBar($output, $this->countSql($se, $sql));

        $i = 0;
        $em = $this->getDefaultManager();
        $stmt = $se->query($sql);
        while ($row = $stmt->fetch()) {
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
                    ->setTelefono($row['telefono'])
                    ->setCelular($row['celular'])
                    ->setGrupoSanguineo($row['grupo_sanguineo'])
                    ->setFactorRh($row['factor_rh'])
                    ->setNacionalidad($row['nacionalidad'])
                    ->setEmailAlt($row['email_alt'])
                    ->setAspiracionSueldo($row['aspiracion_sueldo'])
                    ->setEstatura($row['estatura'])
                    ->setPeso($row['peso'])
                    ->setPersonasCargo($row['personas_cargo'])
                    ->setIdentificacionTipo($row['ident_tipo_id']);
                
                $nacimiento = $row['nacimiento'];
                if($nacimiento){
                    $hv->setNacimiento(\DateTime::createFromFormat('Y-m-d', $nacimiento));
                }
                $hv
                    ->setLmilitarClase($row['lmilitar_clase'])
                    ->setLmilitarNumero($row['lmilitar_numero'])
                    ->setLmilitarDistrito($row['lmilitar_distrito'])
                    ->setPresupuestoMensual($row['presupuesto_mensual'])
                    ->setDeudas($row['deudas'] == 1)
                    ->setDeudasConcepto($row['deudas_concepto'])
                    ->setNivelAcademico($row['nivel_academico_id']);

                $this->setHvAdjunto($hv, $row['adjunto']);
                $em->persist($hv);
                if (($i % $batchSize) === 0) {
                    $em->flush(); // Executes all updates.
                    $em->clear(); // Detaches all objects from Doctrine!
                }
                ++$i;
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->io->writeln('');
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

    protected function setHvAdjunto(Hv $hv, $oldFileName)
    {
        if($oldFileName) {
            if ($this->privateFileSystem->has('hv_adjunto_old/' . $oldFileName)) {
                try {
                    $mimeType = $this->privateFileSystem->getMimetype('hv_adjunto_old/' . $oldFileName);
                    if(!$mimeType) {
                        $mimeType = 'application/octet-stream';
                    }

                    $filePath = $this->projectDir . '/var/uploads/hv_adjunto_old/' . $oldFileName;
                    $file = new File($filePath);

                    $fileName = $this->uploaderHelper->uploadHvAdjunto($file);
                    $this->privateFileSystem->delete('hv_adjunto_old/' . $oldFileName);

                    $hvAdjunto = (new HvAdjunto())
                        ->setFilename($fileName)
                        ->setOriginalFilename($oldFileName)
                        ->setMimeType($mimeType);

                    $hv->setAdjunto($hvAdjunto);
                } catch (\Exception $e) {
                    $usuario = $hv->getUsuario()->getNombreCompleto(true) . " [".$hv->getUsuario()->getIdentificacion()."] ";
                    $this->io->error($usuario . get_class($e) . ": " .$e->getMessage());
                }
            } else {
                $this->io->warning($hv->getUsuario()->getNombreCompleto(true)
                    . " [".$hv->getUsuario()->getIdentificacion()."] adjunto '$oldFileName' no existe");
            }
        }
    }
}
