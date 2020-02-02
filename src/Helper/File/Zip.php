<?php


namespace App\Helper\File;


use Exception;
use League\Flysystem\FilesystemInterface;
use ZipArchive;

trait Zip
{

    protected function zip(FilesystemInterface $filesystem, $zipPath, $filesPath, $pathPrefix = "")
    {
        $zip = new ZipArchive();
        if (!$filesystem->has($zipPath)) {
            $filesystem->write($zipPath, "");
        }
        if ($zip->open($pathPrefix.$zipPath, ZipArchive::OVERWRITE) !== TRUE) {
            throw new Exception("no se puede abrir <" . $zipPath . ">\n");
        }

        foreach ($filesystem->listContents($filesPath, true) as $object) {
            $zip->addFile($pathPrefix . $object['path'], $object['basename']);
        }

        if($zip->status !== 0) {
            throw new Exception("Error zip status 0");
        }
        $zip->close();
        return $zipPath;
    }

}