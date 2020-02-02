<?php


namespace App\Helper;


use Aws\S3\S3Client;

trait S3Helper
{
    /**
     * @var S3Client
     */
    protected $s3Client;
    /**
     * @var string
     */
    protected $s3BucketName;

    /**
     * @param S3Client $s3Client
     * @required
     */
    public function setS3Client(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    /**
     * @param string $s3BucketName
     * @required
     */
    public function setBucketName(string $s3BucketName)
    {
        $this->s3BucketName = $s3BucketName;
    }

    public function generateLink($path, $expires = '+20 minutes'): string
    {
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->s3BucketName,
            'Key' => $path
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, $expires);

        // Get the actual presigned-url
        return (string)$request->getUri();
    }

}