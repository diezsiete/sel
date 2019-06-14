<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const HV_ADJUNTO = 'hv_adjunto';

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

    public function uploadHvAdjunto(File $file, ?string $existingFilename): string
    {
        return $this->uploadFile($file, self::HV_ADJUNTO, false, $existingFilename);
    }

    /**
     * @resource
     */
    public function readStream(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->publicFilesystem : $this->privateFilesystem;
        $resource = $filesystem->readStream($path);
        if($resource === false) {
            throw new \Exception(sprintf("Error abriendo stream para '%s'", $path));
        }
        return $resource;
    }

    private function uploadFile(File $file, string $directory, bool $isPublic, ?string $existingFilename): string
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
