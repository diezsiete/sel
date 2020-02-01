<?php
namespace App\Service\File;

use App\Exception\UploadedFileValidationErrorsException;
use App\Helper\FileHelper;
use App\Helper\S3Helper;
use Exception;
use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
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

    public function __construct(FilesystemInterface $selFilesystem, ValidatorInterface $validator)
    {
        $this->filesystem = $selFilesystem;
        $this->validator = $validator;
    }

    public function uploadFile(File $file, ?string $directory = ""): string
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
}