<?php

namespace App\Command\Migration;

use App\Entity\Hv;
use App\Entity\HvAdjunto;
use App\Repository\HvRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Persistence\ManagerRegistry;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\File\File;

class MigrationHvAdjuntoCommand extends MigrationCommand
{
    protected static $defaultName = 'migration:hv-adjunto';
    /**
     * @var HvRepository
     */
    private $hvRepository;
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

    public function __construct(ManagerRegistry $managerRegistry, FilesystemInterface $privateUploadFilesystem,
                                UploaderHelper $uploaderHelper, string $kernelProjectDir)
    {
        parent::__construct($managerRegistry);
        $this->privateFileSystem = $privateUploadFilesystem;
        $this->projectDir = $kernelProjectDir;
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de archivos adjuntos HV');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sql = "SELECT * "
            . "FROM `hv` hv "
            . "ORDER BY hv.id DESC ";

        $sql = $this->addLimitToSql($sql);
        $this->initProgressBar($this->countSql($sql));

        while ($row = $this->fetch($sql)) {
            $hv = $this->getHvByUsuarioIdOld($row['usuario_id']);
            if($hv) {
                $this->setHvAdjunto($hv, $row['adjunto']);
                $this->selPersist($hv);
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
            }
        }
    }
}
