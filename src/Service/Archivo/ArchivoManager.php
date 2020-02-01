<?php


namespace App\Service\Archivo;


use App\Entity\Archivo\Archivo;
use App\Entity\Main\Usuario;
use App\Exception\UploadedFileValidationErrorsException;
use App\Helper\S3Helper;
use App\Repository\Archivo\ArchivoRepository;
use App\Service\File\FileManager;
use Aws\S3\S3Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchivoManager
{
    use S3Helper {
        generateLink as s3HelperGenerateLink;
    }

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
     * @var S3Client
     */
    private $s3Client;
    /**
     * @var string
     */
    private $s3BucketName;

    public function __construct(FileManager $fileManager, EntityManagerInterface $em, ArchivoRepository $archivoRepository,
                                ValidatorInterface $validator, S3Client $s3Client, string $s3BucketName)
    {
        $this->fileManager = $fileManager;
        $this->em = $em;
        $this->archivoRepository = $archivoRepository;
        $this->validator = $validator;
        $this->s3Client = $s3Client;
        $this->s3BucketName = $s3BucketName;
    }

    /**
     * @param UploadedFile $file
     * @param Usuario $owner
     * @return Archivo
     * @throws UploadedFileValidationErrorsException
     */
    public function uploadArchivo(UploadedFile $file, Usuario $owner)
    {
        $uniqueFilename = $this->fileManager->uploadUploadedFile($file, $this->directory($owner));

        $archivo = (new Archivo())
            ->setFilename($uniqueFilename)
            ->setOriginalFilename($this->fileManager->getOriginalFilenameWithoutExtension($file))
            ->setMimeType($file->getMimeType())
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
                $this->fileManager->delete($this->directory($archivo->getOwner()) . "/{$archivo->getFilename()}");
                $this->em->remove($archivo);
            }
        }
        $this->em->flush();
        return true;
    }

    private function directory(Usuario $owner)
    {
        return "archivo/{$owner->getIdentificacion()}";
    }

    public function generateLink(Archivo $archivo)
    {
        $path = $this->directory($archivo->getOwner()) . "/" . $archivo->getFilename();
        return $this->s3HelperGenerateLink($this->s3Client, $this->s3BucketName, $path);
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
}