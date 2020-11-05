<?php
namespace App\Service\File;

use Akeeba\Engine\Postproc\Connector\S3v4\Connector;
use App\Exception\UploadedFileValidationErrorsException;
use App\Helper\File\File;
use App\Helper\File\S3File;
use App\Helper\FileHelper;
use App\Service\Utils\File as FileUtil;
use Exception;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileManager
{
    use FileHelper;

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FileUtil
     */
    private $fileUtil;
    /**
     * @var Connector
     */
    public $s3Connector;

    public function __construct(
        FilesystemInterface $selFilesystem,
        ValidatorInterface $validator,
        FileUtil $fileUtil,
        Connector $s3Connector
    ){
        $this->filesystem = $selFilesystem;
        $this->validator = $validator;
        $this->fileUtil = $fileUtil;
        $this->s3Connector = $s3Connector;
    }

    public function uploadFile(SymfonyFile $file, ?string $directory = ""): string
    {
        $newFilename = $this->generateUniqueFilename($file);

        $stream = fopen($file->getPathname(), 'r');

        $result = $this->filesystem->writeStream(($directory ? "$directory/" : "")  . $newFilename, $stream);
        if($result === false) {
            throw new Exception(sprintf('Could not write uploaded file "%s"', $this->getOriginalFilename($file)));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    /**
     * @param UploadedFile $file
     * @param string $directory
     * @param array $validations
     * @return string
     * @throws UploadedFileValidationErrorsException
     */
    public function uploadUploadedFile(UploadedFile $file, $directory = "", $validations = [])
    {
        if(is_array($directory)) {
            $validations = $directory;
            $directory = "";
        } else {
            $validations = $validations ? $validations : [
                new NotBlank(),
                new FileConstraint()
            ];
        }

        $errors = $this->validator->validate($file, $validations);
        if (count($errors) > 0) {
            throw UploadedFileValidationErrorsException::create($errors);
        }

        return $this->uploadFile($file, $directory);
    }

    /**
     * @param $filePath
     * @return null|false|string
     */
    public function delete($filePath)
    {
        $reportDeleted = null;
        if($this->filesystem->has($filePath)) {
            try {
                $this->filesystem->delete($filePath);
                $reportDeleted = $filePath;
            } catch (FileNotFoundException $e) {
                $reportDeleted = false;
            }
        }
        return $reportDeleted;
    }

    /**
     * @param string|SymfonyFile|UploadedFile $file
     * @return File|S3File
     */
    public function file($file)
    {
        $adapter = $this->filesystem->getAdapter();
        if(strpos(get_class($adapter), 'S3') !== false) {
            /** @var AwsS3Adapter $adapter */
            $file = new S3File($this->filesystem, $this->fileUtil, $adapter->getClient(), $adapter->getBucket(), $this->s3Connector, $file);
        } else {
            $file = new File($this->filesystem, $this->fileUtil, $file);
        }
        return $file;
    }
}