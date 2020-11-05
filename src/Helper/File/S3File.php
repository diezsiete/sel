<?php


namespace App\Helper\File;


use Akeeba\Engine\Postproc\Connector\S3v4\Connector;
use App\Service\Utils\File as FileUtil;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use DateTime;
use League\Flysystem\FilesystemInterface;

class S3File extends File
{
    /**
     * @var S3Client
     */
    private $s3Client;
    /**
     * @var string
     */
    private $s3BucketName;
    /**
     * @var Connector
     */
    private $s3Connector;

    /**
     * S3File constructor.
     * @param FilesystemInterface $filesystem
     * @param FileUtil $fileUtil
     * @param S3Client|S3ClientInterface $s3Client
     * @param string $s3BucketName
     * @param Connector $s3Connector
     * @param $file
     */
    public function __construct(FilesystemInterface $filesystem, FileUtil $fileUtil, S3Client $s3Client, string $s3BucketName, Connector $s3Connector, $file)
    {
        parent::__construct($filesystem, $fileUtil, $file);
        $this->s3Client = $s3Client;
        $this->s3BucketName = $s3BucketName;
        $this->s3Connector = $s3Connector;
    }

    /**
     * @param string|integer|DateTime $expires URL expiration. Can be Unix timestamp, DateTime, or string compatible with strtotime()
     * @return string
     */
    public function generateLink($expires = '+20 minutes'): string
    {   // si expires es mayor a 7 dias debemos utilizar v2 de s3
        $origin = new \DateTime();
        $target = is_object($expires)
            ? $expires
            : (new \DateTime())->setTimestamp(is_int($expires) ? $expires : strtotime($expires));
        $interval = $origin->diff($target);
        if((int)$interval->format('%a') > 7) {
            return $this->s3Connector->getAuthenticatedURL($this->s3BucketName, $this->getPathname(), $interval->format('%a') * 24 * 60 * 60);
        }

        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->s3BucketName,
            'Key' => $this->getPathname()
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, $expires);

        // Get the actual presigned-url
        return (string)$request->getUri();
    }
}