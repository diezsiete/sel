<?php


namespace App\Helper\File;


use Exception;
use League\Flysystem\FilesystemInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

trait Zip
{

    protected function zip(FilesystemInterface $filesystem, $zipPath, $filesPath, $pathPrefix = '')
    {
        $zip = new ZipArchive();
        if (!$filesystem->has($zipPath)) {
            $filesystem->write($zipPath, '');
        }
        if ($zip->open($pathPrefix.$zipPath, ZipArchive::OVERWRITE) !== TRUE) {
            throw new \RuntimeException('no se puede abrir <' . $zipPath . ">\n");
        }

        foreach ($filesystem->listContents($filesPath, true) as $object) {
            $zip->addFile($pathPrefix . $object['path'], $object['basename']);
        }

        if($zip->status !== 0) {
            throw new \RuntimeException('Error zip status 0');
        }
        $zip->close();
        return $zipPath;
    }

    protected function zipDirectory(FilesystemInterface $filesystem, $zipPath, $directory, $pathPrefix = ''): string
    {
        $zip = new ZipArchive();
        if (!$filesystem->has($zipPath)) {
            $filesystem->write($zipPath, '');
        }
        if ($zip->open($pathPrefix.$zipPath, ZipArchive::OVERWRITE) !== TRUE) {
            throw new \RuntimeException('no se puede abrir <' . $zipPath . ">\n");
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($pathPrefix.$directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($pathPrefix.$directory) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        if($zip->status !== 0) {
            throw new \RuntimeException('Error zip status 0');
        }
        $zip->close();
        return $pathPrefix.$zipPath;
    }

}