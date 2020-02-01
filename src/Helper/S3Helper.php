<?php


namespace App\Helper;


use Aws\S3\S3Client;

trait S3Helper
{
    public function generateLink(S3Client $s3Client, $bucketName, $path, $expires = '+20 minutes'): string
    {
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $bucketName,
            'Key' => $path
        ]);

        $request = $s3Client->createPresignedRequest($cmd, $expires);

        // Get the actual presigned-url
        return (string)$request->getUri();
    }
}