<?php


namespace App\Helper\File;


use App\Service\Utils\File as FileUtil;
use Exception;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;
    /**
     * @var string|SymfonyFile|UploadedFile
     */
    protected $file;
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var FileUtil
     */
    protected $fileUtil;
    /**
     * @var false|resource
     */
    protected $stream;

    protected $path = '';

    /**
     * FileUpload constructor.
     * @param FilesystemInterface $filesystem
     * @param FileUtil $fileUtil
     * @param string|SymfonyFile|UploadedFile $file
     */
    public function __construct(FilesystemInterface $filesystem, FileUtil $fileUtil, $file)
    {
        $this->filesystem = $filesystem;
        $this->fileUtil = $fileUtil;
        $this->file = $file;
    }

    /**
     * @param string $path
     * @return $this
     * @throws Exception
     */
    public function upload($path = '')
    {
        $this->path = $path && substr($path,-1) === '/' ? substr($path, 0, -1) : $path;
        $path = $this->getPathname();

        if($this->filesystem->has($path)) {
            $this->filesystem->delete($path);
        }
        $result = $this->filesystem->writeStream($path, $this->fopen());
        if($result === false) {
            throw new Exception(sprintf('Could not write uploaded file "%s"', $this->getOriginalFilename()));
        }

        return $this->close();
    }

    /**
     * @return string
     */
    public function getOriginalPathname()
    {
        if(is_object($this->file)) {
            return $this->file->getPathname();
        }
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPathname()
    {
        if($this->path && substr($this->path, 0, 1) === '/') {
            $this->path = substr($this->path, 1);
        }
        return ($this->path ? $this->path . "/" : "") . $this->getFilename() . ($this->getExtension() ? '.'.$this->getExtension() : '');
    }

    /**
     * El nombre del archivo que puede ser el original o modificado por ej. generateUniqueFilename
     * @return mixed|string
     */
    public function getFilename()
    {
        if($this->filename === null) {
            $this->filename = $this->getOriginalFilename();
        }
        return $this->filename;
    }

    public function getOriginalFilename()
    {
        return $this->fileUtil->getOriginalFilename($this->file);
    }

    /**
     * @return string|null
     */
    public function getExtension()
    {
        return $this->fileUtil->getExtension($this->file);
    }

    /**
     * @param null $format
     * @param bool $appendFormat
     * @return float|int|string
     */
    public function getSize($format = null, $appendFormat = false)
    {
        return $this->fileUtil->getSize($this->file, $format, $appendFormat);
    }

    public function generateUniqueFilename()
    {
        $this->filename = $this->fileUtil->generateUniqueFilename($this->file);
        return $this;
    }

    /**
     * @return false|resource
     */
    public function fopen()
    {
        $this->stream = fopen($this->getOriginalPathname(), 'r');
        return $this->stream;
    }

    /**
     * @return $this
     */
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        return $this;
    }
}