<?php


namespace App\Service\Utils;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\FilesystemInterface;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class File
{
    /**
     * @var System
     */
    private $systemUtils;

    public function __construct(System $systemUtils)
    {
        $this->systemUtils = $systemUtils;
    }


    /**
     * @param SymfonyFile|UploadedFile|string|SplFileInfo $file
     * @return string
     */
    public function generateUniqueFilename($file)
    {
        $originalFilename = $this->getOriginalFilename($file);
        $extension = $this->getExtension($file);
        return Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid('', true) . '.' . $extension;
    }

    /**
     * @param SymfonyFile|UploadedFile|string|SplFileInfo $file
     * @return string
     */
    public function getOriginalFilename($file)
    {
        if(is_object($file)) {
            return $file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename();
        }
        $pathinfo = pathinfo($file);
        return $pathinfo['filename'];
    }

    /**
     * @param SymfonyFile|UploadedFile|string|SplFileInfo $file
     * @return null|string
     */
    public function getExtension($file)
    {
        $extension = null;
        if(is_object($file)) {
            $extension = $file->getExtension();
            if (!$extension && $file instanceof SymfonyFile) {
                $extension = $file->guessExtension();
            }
        } else {
            $pathinfo = pathinfo($file);
            return $pathinfo['extension'];
        }
        return $extension;
    }

    /**
     * @param SymfonyFile|UploadedFile|string|SplFileInfo $file
     * @param null|string $format 'KB', 'MB', 'GB', 'TB'
     * @param bool $appendFormat
     * @return int|float|string
     */
    public function getSize($file, $format = null, $appendFormat = false)
    {
        $size = is_object($file) ? $file->getSize() : filesize($file);
        return $format ? $this->byteToSize($size, $format, $appendFormat) : $size;
    }

    /**
     * TODO por ahora creo que solo funciona con filesystem local, probar con AWS
     * @param FilesystemInterface $filesystem
     * @param string|null $prependPath
     * @return string
     */
    public function getFilesystemPathPrefix(FilesystemInterface $filesystem, ?string $prependPath = null)
    {
        /** @var AbstractAdapter $adapter */
        $adapter = $filesystem->getAdapter();
        return $adapter->getPathPrefix() . ($prependPath ? $prependPath : "");
    }


    /**
     * @param int $bytes
     * @param string $format 'KB', 'MB', 'GB', 'TB'
     * @param bool $appendFormat
     * @param int $roundLength
     * @return float|string
     */
    function byteToSize($bytes, $format = null, $appendFormat = false, $roundLength = 1)
    {
        $kb = 1024;
        $mb = 1024 * $kb;
        $gb = 1024 * $mb;
        $tb = 1024 * $gb;

        $size = $bytes;
        if($bytes > 0) {
            if(!$format) {
                if($bytes < $kb) {
                    $format = 'B';
                } else if($bytes < $mb) {
                    $format = 'KB';
                } else if($bytes < $gb) {
                    $format = 'MB';
                } else if($bytes < $tb) {
                    $format = 'GB';
                } else {
                    $format = 'TB';
                }
            }
            switch ($format) {
                case 'KB':
                    $size = round($bytes / $kb, $roundLength);
                    break;
                case 'MB':
                    $size = round($bytes / $mb, $roundLength);
                    break;
                case 'GB':
                    $size = round($bytes / $gb, $roundLength);
                    break;
                case 'TB':
                    $size = round($bytes / $tb,$roundLength);
                    break;
            }
        }
        return $appendFormat ? $size . " $format" : $size;
    }
}