<?php

namespace App\Command\Migration;

use App\Entity\Hv;
use App\Entity\HvAdjunto;
use App\Repository\HvRepository;
use App\Service\UploaderHelper;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\File;

class MigrationHvAdjuntoCommand extends MigrationCommand
{
    protected static $defaultName = 'sel:migration:hv-adjunto';
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
    /**
     * @var string
     */
    private $migrationHvAdjuntoPath;

    public function __construct(Reader $annotationReader, EventDispatcherInterface $eventDispatcher,
                                ManagerRegistry $managerRegistry, UploaderHelper $uploaderHelper,
                                string $kernelProjectDir, FilesystemInterface $migrationHvAdjuntoFilesystem, $migrationHvAdjuntoPath)
    {
        parent::__construct($annotationReader, $eventDispatcher, $managerRegistry);
        $this->privateFileSystem = $migrationHvAdjuntoFilesystem;
        $this->projectDir = $kernelProjectDir;
        $this->uploaderHelper = $uploaderHelper;
        $this->migrationHvAdjuntoPath = $migrationHvAdjuntoPath;
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Migracion de archivos adjuntos HV');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $sql = "SELECT * FROM hv WHERE adjunto IS NOT NULL";

        $sql = $this->addLimitToSql($sql);
        $count = $this->countSql($sql, self::CONNECTION_SE_ASPIRANTE);
        $this->initProgressBar($count);

        while ($row = $this->fetch($sql, self::CONNECTION_SE_ASPIRANTE)) {
            $hv = $this->getHvByUsuarioIdOld($row['usuario_id']);
            if($hv) {
                $this->setHvAdjunto($hv, $row['adjunto']);
                $this->selPersist($hv);
            }

        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(HvAdjunto::class);
        $this->uploaderHelper->deleteDirHvAdjunto();
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
            if ($this->privateFileSystem->has($oldFileName)) {
                try {
                    $mimeType = $this->privateFileSystem->getMimetype($oldFileName);
                    if(!$mimeType) {
                        $mimeType = 'application/octet-stream';
                    }

                    $filePath = $this->migrationHvAdjuntoPath . '/' . $oldFileName;
                    $file = new File($filePath);

                    $fileName = $this->uploaderHelper->uploadHvAdjunto($file);
                    

                    $hvAdjunto = (new HvAdjunto())
                        ->setFilename($fileName)
                        ->setOriginalFilename($oldFileName)
                        ->setMimeType($mimeType);

                    $hv->setAdjunto($hvAdjunto);
                } catch (Exception $e) {
                    $usuario = $hv->getUsuario()->getNombreCompleto(true) . " [".$hv->getUsuario()->getIdentificacion()."] ";
                    $this->io->error($usuario . get_class($e) . ": " .$e->getMessage());
                }
            }
        }
    }

    
}
