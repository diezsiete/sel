<?php


namespace App\Helper;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Constant\File as FileConstant;

trait FileHelper
{
    /**
     * @param File $file
     * @return string|null
     */
    public function getOriginalFilename($file)
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        return $originalFilename;
    }

    /**
     * @param File|$file
     * @return string|null
     */
    public function getOriginalFilenameWithoutExtension($file)
    {
        $originalFilename = $this->getOriginalFilename($file);
        if($originalFilename) {
            $extension = $this->getExtension($file);
            if (strpos($originalFilename, $extension) !== false) {
                $originalFilename = str_replace(".$extension", "", $originalFilename);
            } else {
                $originalFilename = preg_replace('/(.+)\.\w+$/', '$1', $originalFilename);
            }
        }
        return $originalFilename;
    }

    /**
     * @param File $file
     * @return mixed
     */
    public function getExtension($file)
    {
        $extension = $file->getExtension();
        if(!$extension) {
            $extension = $file->guessExtension();
        }
        return $extension;
    }

    /**
     * @param File $file
     * @return string
     */
    public function generateUniqueFilename($file)
    {
        $originalFilename = $this->getOriginalFilename($file);
        $extension = $this->getExtension($file);
        return Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $extension;
    }

    /**
     * @param File $file
     * @param bool $useExtensionForMicrosoft Los mimeTypes de microsoft son muy largos, dejar solo la extension
     * @return string|null
     */
    public function getMimeType($file, $useExtensionForMicrosoft = false)
    {
        $mimeType = $file->getMimeType();
        if($mimeType && $useExtensionForMicrosoft && preg_match("/^(application\/mswor|application\/vnd).+/", $mimeType)) {
            if($extension = array_search($mimeType, FileConstant::microsoftMimeTypes)) {
                $mimeType = $extension;
            }
        }
        return $mimeType;
    }

    /**
     * TODO por ahora creo que solo funciona con filesystem local, probar con AWS
     * @param FilesystemInterface $filesystem
     * @param string|null $prependPath
     * @return string
     */
    protected function getFilesystemPathPrefix(FilesystemInterface $filesystem, ?string $prependPath = null)
    {
        /** @var AbstractAdapter $adapter */
        $adapter = $filesystem->getAdapter();
        return $adapter->getPathPrefix() . ($prependPath ? $prependPath : "");
    }
}