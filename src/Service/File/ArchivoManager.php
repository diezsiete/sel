<?php


namespace App\Service\File;


use App\Entity\Archivo\Archivo;
use App\Entity\Main\Usuario;
use App\Exception\UploadedFileValidationErrorsException;
use App\Helper\File\Zip;
use App\Helper\S3Helper;
use App\Repository\Archivo\ArchivoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchivoManager extends FileManager
{
    use S3Helper {
        generateLink as s3HelperGenerateLink;
    }
    use Zip;

    /**
     * @var FileManager
     */
    private $fileManager;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ArchivoRepository
     */
    private $archivoRepository;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FilesystemInterface
     */
    private $localFilesystem;

    public function __construct(FilesystemInterface $selFilesystem, ValidatorInterface $validator,
                                FilesystemInterface $localFilesystem, EntityManagerInterface $em,
                                ArchivoRepository $archivoRepository)
    {
        parent::__construct($selFilesystem, $validator);

        $this->em = $em;
        $this->archivoRepository = $archivoRepository;
        $this->validator = $validator;
        $this->localFilesystem = $localFilesystem;
    }

    /**
     * @param UploadedFile $file
     * @param Usuario $owner
     * @return Archivo
     * @throws UploadedFileValidationErrorsException
     */
    public function uploadArchivo(UploadedFile $file, Usuario $owner)
    {
        $uniqueFilename = $this->fileManager->uploadUploadedFile($file, $this->path($owner));

        $archivo = (new Archivo())
            ->setFilename($uniqueFilename)
            ->setOriginalFilename($this->fileManager->getOriginalFilenameWithoutExtension($file))
            ->setMimeType($this->fileManager->getMimeType($file, true))
            ->setSize($file->getSize())
            ->setExtension($this->fileManager->getExtension($file))
            ->setOwner($owner);

        $this->em->persist($archivo);
        $this->em->flush();

        return $archivo;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        $ids = is_array($id) ? $id : [$id];
        foreach($ids as $id) {
            if ($archivo = $this->archivoRepository->find($id)) {
                $this->fileManager->delete($this->path($archivo));
                $this->em->remove($archivo);
            }
        }
        $this->em->flush();
        return true;
    }


    /**
     * @param Archivo|int $archivo
     * @return string
     */
    public function generateLink($archivo)
    {
        $archivo = is_object($archivo) ? $archivo : $this->archivoRepository->find($archivo);
        return $this->s3HelperGenerateLink($this->path($archivo));
    }

    public function updateOriginalFilename(Archivo $archivo, $newOriginalFilename)
    {
        $archivo->setOriginalFilename($newOriginalFilename);
        $errors = $this->validator->validate($archivo);
        if (count($errors) > 0) {
            throw UploadedFileValidationErrorsException::create($errors);
        }
        $this->em->flush();
        return $archivo;
    }

    /**
     * @param Usuario $owner
     * @return ArchivoManager
     */
    public function downloadLocalClearDirectory(Usuario $owner)
    {
        $path = $this->path($owner);
        $this->localFilesystem->deleteDir($path);
        return $this;
    }

    /**
     * @param Archivo|int $archivo
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileExistsException
     */
    public function downloadLocal($archivo)
    {
        $archivo = is_object($archivo) ? $archivo : $this->archivoRepository->find($archivo);
        if($archivo) {
            $sourcePath = $this->path($archivo);
            $resource = $this->filesystem->readStream($sourcePath);
            if($resource === false) {
                throw new Exception(sprintf("Error abriendo stream para '%s'", $sourcePath));
            }

            $targetPath = $this->downloadLocalUniqueTargetPath($archivo);

            $result = $this->localFilesystem->writeStream($targetPath, $resource);
            if($result === false) {
                throw new Exception(sprintf('No se puede descargar "%s"', $targetPath));
            }
            if (is_resource($resource)) {
                fclose($resource);
            }
        }
    }

    public function downloadLocalZip(Usuario $usuario)
    {
        return $this->zip(
            $this->localFilesystem,
            $this->path($usuario) . ".zip",
            $this->path($usuario),
            $this->getFilesystemPathPrefix($this->localFilesystem)
        );
    }

    public function downloadLocalZipPath(Usuario $usuario)
    {
        return $this->path($usuario, $this->localFilesystem) . ".zip";
    }

    private function downloadLocalUniqueTargetPath(Archivo $archivo)
    {
        $targetPath = $this->path($archivo->getOwner()) . "/{$archivo->getOriginalFilename()}.{$archivo->getExtension()}";
        $counter = 1;
        while($this->localFilesystem->has($targetPath)) {
            $targetPath = $this->path($archivo->getOwner()) . "/{$archivo->getOriginalFilename()}-{$counter}.{$archivo->getExtension()}";
            $counter++;
        }
        return $targetPath;
    }

    /**
     * @param Usuario|Archivo|null $ownerOrArchivo
     * @param FilesystemInterface|null $absolute
     * @return string
     */
    private function path($ownerOrArchivo = null, ?FilesystemInterface $absolute = null)
    {
        $path = "archivo";
        if($ownerOrArchivo) {
            $archivo = $ownerOrArchivo instanceof Archivo ? $ownerOrArchivo : null;
            $owner = $archivo ? $archivo->getOwner() : $ownerOrArchivo;
            $path = "{$path}/{$owner->getIdentificacion()}" . ($archivo ? "/{$archivo->getFilename()}" : "");
        }

        return $absolute ? $this->getFilesystemPathPrefix($absolute, $path) : $path;
    }
}