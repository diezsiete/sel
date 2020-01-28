<?php
namespace App\Service\File;

use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    public function __construct(FilesystemInterface $selFilesystem)
    {
        $this->filesystem = $selFilesystem;
    }

    public function uploadFile(File $file, ?string $directory = ""): string
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

        $stream = fopen($file->getPathname(), 'r');

        $result = $this->filesystem->writeStream(($directory ? "$directory/" : "")  . $newFilename, $stream);
        if($result === false) {
            throw new Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }
}