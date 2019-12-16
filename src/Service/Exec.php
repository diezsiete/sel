<?php


namespace App\Service;

use Exception;
use League\Flysystem\FilesystemInterface;

class Exec
{
    /**
     * @var FilesystemInterface
     */
    private $fileSystem;
    private $kernelProjectDir;
    private $privateUploadsBaseUrl;

    public function __construct(FilesystemInterface $privateUploadFilesystem, $kernelProjectDir, $privateUploadsBaseUrl)
    {
        $this->fileSystem = $privateUploadFilesystem;
        $this->kernelProjectDir = $kernelProjectDir;
        $this->privateUploadsBaseUrl = $privateUploadsBaseUrl;
    }

    /**
     * @param $command
     * @throws Exception
     */
    public function async($command)
    {
        $this->createIfNotExists($this->getFile("out", null, false));
        $this->createIfNotExists($this->getFile("pid", null, false));

        $this->execAsync($command, $this->getFile("out"), $this->getFile("pid"));
    }

    public function asyncUnique($command, $uniqueName)
    {
        $outputFile = $this->getFile("out", $uniqueName, false);
        $pidFile = $this->getFile("pid", $uniqueName, false);

        if($this->fileSystem->has($outputFile) || $this->fileSystem->has($pidFile)) {
            throw new Exception("unique command '$uniqueName' has not been terminated. cant run command '$command'");
        }
        $this->createIfNotExists($outputFile);
        $this->createIfNotExists($pidFile);

        $this->execAsync($command, $this->getFile("out", $uniqueName), $this->getFile("pid", $uniqueName));
    }

    public static function sync($command)
    {
        exec($command, $output, $return);
        return $return;
    }

    public function killUnique($uniqueName) {
        $pid = $this->readPid($uniqueName);
        if($pid) {
            $return = $this->sync(sprintf("kill %d", $pid));
            if($return) {
                throw new Exception("kill returned not 0 value");
            }
            $this->deleteUniqueFiles($uniqueName);
        }
    }

    public function deleteUniqueFiles($uniqueName)
    {
        $this->fileSystem->delete($this->getFile("out", $uniqueName, false));
        $this->fileSystem->delete($this->getFile("pid", $uniqueName, false));
    }

    public function uniqueExists($uniqueName)
    {
        return $this->fileSystem->has($this->getFile("out", $uniqueName, false))
            || $this->fileSystem->has($this->getFile("pid", $uniqueName, false));
    }

    public function readPid($uniqueName = ""): ?int
    {
        $path = $this->getFile("pid", $uniqueName, false);
        $pid = null;
        if($this->fileSystem->has($path)) {
            $pid = $this->fileSystem->read($path);
        }
        return $pid ? (int)$pid : null;
    }

    private function getFile($fileKind, $uniqueName = "", $full = true)
    {
        $path = "/exec/$fileKind.txt";
        if($uniqueName) {
            $path = "/exec/$uniqueName-$fileKind.txt";
        }
        return $full ? $this->kernelProjectDir . $this->privateUploadsBaseUrl . $path : $path;
    }

    private function createIfNotExists($path)
    {
        if (!$this->fileSystem->has($path)) {
            $this->fileSystem->write($path, "");
        }
    }

    private function execAsync($command, $outputFile, $pidFile)
    {
        $exec = sprintf("%s > %s 2>&1 & echo $! >> %s", $command, $outputFile, $pidFile);
        exec($exec);
    }
}