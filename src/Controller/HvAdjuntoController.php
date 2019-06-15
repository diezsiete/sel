<?php


namespace App\Controller;


use App\Entity\Hv;
use App\Entity\HvAdjunto;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HvAdjuntoController extends BaseController
{
    /**
     * @Route("/hv-adjunto/{id}/upload", name="hv_adjunto_upload", methods={"POST"})
     * @IsGranted("HV_MANAGE", subject="hv")
     */
    public function upload(Hv $hv, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $em,
                           ValidatorInterface $validator)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('adjunto');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    "message" => "Por favor seleccione un archivo para cargar"
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ],
                    'mimeTypesMessage' => 'Solo se aceptan archivos PDF y Word'
                ])
            ]
        );

        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $adjunto = $hv->getAdjunto() ?? new HvAdjunto();

        $filename = $uploaderHelper->uploadHvAdjunto($uploadedFile, $adjunto->getFilename());

        $adjunto
            ->setHv($hv)
            ->setFilename($filename)
            ->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename)
            ->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

        $em->persist($adjunto);
        $em->flush();

        return $this->json($adjunto, 201, [], [
            'groups' => ['main']
        ]);
    }

    /**
     * @Route("/sel/hv-adjunto/{id}/download", name="hv_adjunto_download", methods={"GET"})
     * @IsGranted("HV_MANAGE", subject="hv")
     */
    public function downloadAdjunto(Hv $hv, UploaderHelper $uploaderHelper)
    {
        $adjunto = $hv->getAdjunto();

        if(!$adjunto) {
            return new Response();
        }
        $response = new StreamedResponse(function() use ($adjunto, $uploaderHelper){
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $uploaderHelper->readStream($adjunto->getFilePath(), false);

            stream_copy_to_stream($fileStream, $outputStream);
        });
        $response->headers->set('Content-Type', $adjunto->getMimeType());
//        $disposition = HeaderUtils::makeDisposition(
//            HeaderUtils::DISPOSITION_ATTACHMENT, $adjunto->getOriginalFilename());
//        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}