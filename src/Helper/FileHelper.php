<?php


namespace App\Helper;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
}