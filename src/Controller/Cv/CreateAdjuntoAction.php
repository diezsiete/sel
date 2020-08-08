<?php


namespace App\Controller\Cv;


use App\Entity\Hv\Adjunto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateAdjuntoAction
{
    public function __invoke(Request $request): Adjunto
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $adjunto = new Adjunto();
        $adjunto->file = $uploadedFile;

        return $adjunto;
    }
}