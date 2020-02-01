<?php


namespace App\Service\ServicioEmpleados\Report;


use Aws\S3\S3Client;
use League\Flysystem\FilesystemInterface;

class PdfHandler
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var S3Client
     */
    private $s3Client;
    /**
     * @var string
     */
    private $s3BucketName;


    public function __construct(FilesystemInterface $seReportCachedFilesystem, S3Client $s3Client, string $s3BucketName)
    {
        //$this->filesystem = $seReportFilesystem;
        $this->filesystem = $seReportCachedFilesystem;
        $this->s3Client = $s3Client;
        $this->s3BucketName = $s3BucketName;
    }

    public function cache(string $reporteNombre, $callbackSource)
    {
        $path = $reporteNombre;
        if (!$this->filesystem->has($path)) {
            return $this->filesystem->write($path, $callbackSource());
        }
        return true;
    }

    public function write(string $reporteNombre, $callbackSource)
    {
        $path = $reporteNombre;
        if ($this->filesystem->has($path)) {
            return $this->filesystem->update($path, $callbackSource());
        } else {
            return $this->filesystem->write($path, $callbackSource());
        }
    }

    public function writeAndStream(string $reporteNombre, $callbackSource)
    {
        $this->write($reporteNombre, $callbackSource);
        return $this->readStream($reporteNombre);
    }

    public function cacheAndStream(string $reporteNombre, $callbackSource)
    {
        $this->cache($reporteNombre, $callbackSource);
        return $this->readStream($reporteNombre);
    }

    public function readStream(string $reporteNombre)
    {
        return $this->filesystem->readStream($reporteNombre);
    }

    public function delete(string $reportNombre)
    {
        $reportDeleted = null;
        if($this->filesystem->has($reportNombre)) {
            $reportDeleted = $reportNombre;
            $this->filesystem->delete($reportNombre);
        }
        return $reportDeleted;
    }

    public function cacheAndLink(string $reporteNombre, $callbackSource)
    {
        $this->cache($reporteNombre, $callbackSource);
        return $this->generateLink($reporteNombre);
    }

    /**
     * TODO utilizar S3Helper Trait
     * @param string $reporteNombre
     * @return string
     * @deprecated
     */
    public function generateLink(string $reporteNombre): string
    {
        $reporteNombre = 'se/report/'. preg_replace('/^\/(.+)/', '$1', $reporteNombre);

        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->s3BucketName,
            'Key' => $reporteNombre
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Get the actual presigned-url
        return (string)$request->getUri();
    }

}