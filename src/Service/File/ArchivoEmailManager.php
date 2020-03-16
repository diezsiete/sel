<?php


namespace App\Service\File;


use App\Repository\Archivo\ArchivoRepository;
use App\Service\Utils\System;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchivoEmailManager extends ArchivoManager
{

    /**
     * @var System
     */
    private $systemUtils;

    public function __construct(FilesystemInterface $selFilesystem, ValidatorInterface $validator,
                                FilesystemInterface $localFilesystem, EntityManagerInterface $em,
                                ArchivoRepository $archivoRepository, System $systemUtils)
    {
        parent::__construct($selFilesystem, $validator, $localFilesystem, $em, $archivoRepository);
        $this->systemUtils = $systemUtils;
    }

    public function createSendFile($owners, $name = 'email')
    {
        $downloadPaths = [];
        foreach ($owners as $ownerId) {
            $archivos = $this->archivoRepository->findAllByOwner($ownerId);
            foreach ($archivos as $archivo) {
                $downloadPaths[] = $this->downloadLocal($archivo, $name);
            }
        }
        return count($downloadPaths) > 0;
    }

    public function createZip($name = 'email'): string
    {
        return $this->zipDirectory(
            $this->localFilesystem,
            $this->path() . '/' . $name . '.zip',
            $this->path() . '/' . $name,
            $this->getFilesystemPathPrefix($this->localFilesystem)
        );
    }

    public function isZipSendable($name = 'email', $maxMb = 25)
    {
        $size = $this->localFilesystem->getSize($this->path() . "/$name.zip");
        return $this->systemUtils->byteToSize($size, 1, 'MB') < $maxMb;
    }

    public function generateZipLink($name, $expires = '+1 week')
    {
        $zipPath = $this->getFilesystemPathPrefix($this->localFilesystem) . $this->path() . '/' . $name . '.zip';
        $zipUploadedName = $this->uploadFile(new File($zipPath), $this->path() . '/email');
        return $this->s3HelperGenerateLink("{$this->path()}/email/$zipUploadedName", $expires);
    }

    public function deleteSendFile($name = 'email')
    {
        if($this->localFilesystem->has("{$this->path()}/$name")) {
            $this->localFilesystem->deleteDir("{$this->path()}/$name");
        }
        if($this->localFilesystem->has("{$this->path()}/$name.zip")) {
            $this->localFilesystem->delete("{$this->path()}/$name.zip");
        }
    }

}