<?php

namespace App\Service;

use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploaderHelper
 * @package App\Service
 * @deprecated
 */
class UploaderHelper
{
    const HV_ADJUNTO = 'hv_adjunto';
    const PRIVATE_FILES = 'private_files';

    /**
     * @var FilesystemInterface
     */
    private $privateFilesystem;
    /**
     * @var FilesystemInterface
     */
    private $publicFilesystem;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(FilesystemInterface $privateUploadFilesystem, FilesystemInterface $publicUploadFilesystem,
                                LoggerInterface $logger)
    {
        $this->privateFilesystem = $privateUploadFilesystem;
        $this->publicFilesystem = $publicUploadFilesystem;
        $this->logger = $logger;
    }

    public function uploadHvAdjunto(File $file, ?string $existingFilename = null): string
    {
        return $this->uploadFile($file, self::HV_ADJUNTO, false, $existingFilename);
    }

    public function uploadPrivateFile(File $file, $getMetadata = false, ?ContainerBagInterface $bag = null)
    {
        $fileName = $this->uploadFile($file, self::PRIVATE_FILES, false);
        if($getMetadata) {
            $metadata =  $this->privateFilesystem->getMetadata(self::PRIVATE_FILES . '/' . $fileName);
            if($bag && $metadata) {
                $metadata['fullpath'] = $bag->get('kernel.project_dir') . '/' . $bag->get('private_uploads_dir_name') . '/'  . $metadata['path'];
            }
            return $metadata;
        }
        return $fileName;
    }

    public function deleteDirHvAdjunto()
    {
        if($this->privateFilesystem->deleteDir(self::HV_ADJUNTO)) {
            $this->privateFilesystem->createDir(self::HV_ADJUNTO);
        } else {
            throw new Exception("Error borrando directorio " . self::HV_ADJUNTO);
        }
    }

    /**
     * @resource
     */
    public function readStream(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->publicFilesystem : $this->privateFilesystem;
        $resource = $filesystem->readStream($path);
        if($resource === false) {
            throw new Exception(sprintf("Error abriendo stream para '%s'", $path));
        }
        return $resource;
    }

    private function uploadFile(File $file, string $directory, bool $isPublic, ?string $existingFilename = null): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();

        }
        $extension = $file->getExtension();
        if(!$extension) {
            $extension = $file->guessExtension();
        }
        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)).'-'.uniqid().'.'.$extension;

        $fileSystem = $isPublic ? $this->publicFilesystem : $this->privateFilesystem;

        $stream = fopen($file->getPathname(), 'r');
        $result = $fileSystem->writeStream(
            $directory . '/' . $newFilename,
            $stream
        );
        if($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }

        if($existingFilename) {
            try {
                $result = $fileSystem->delete($directory . '/' . $existingFilename);
                if($result === false) {
                    throw new \Exception(sprintf("Could not delete old hv adjunto file '%s'", $existingFilename));
                }
            }catch(FileNotFoundException $e) {
                $this->logger->alert(sprintf("Old hv adjunto file '%s' was missing when trying to delete", $existingFilename));
            }
        }

        return $newFilename;

    }
}
