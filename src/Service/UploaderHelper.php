<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    const HV_ADJUNTO = 'hv_adjunto';

    /**
     * @var FilesystemInterface
     */
    private $privateFileSystem;

    public function __construct(FilesystemInterface $privateUploadFileSystem)
    {
        $this->privateFileSystem = $privateUploadFileSystem;
    }

    public function uploadHvAdjunto(File $file): string
    {
        return $this->uploadFile($file, self::HV_ADJUNTO, false);
    }


    private function uploadFile(File $file, string $directory, bool $isPublic): string
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


        if(!$isPublic) {
            $fileSystem = $this->privateFileSystem;
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

            return $newFilename;
        }
    }
}
