<?php


namespace App\Controller\Admin\Api;


use App\Controller\BaseController;
use App\Service\File\FileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArchivoController extends BaseController
{

    /**
     * @Route("/sel/admin/api/archivo", methods="GET", name="sel_admin_api_archivo_list", options={"expose" = true})
     */
    public function list()
    {
        return $this->json([
            'items' => []
        ]);
    }

    /**
     * @Route("/sel/admin/api/archivo", methods="POST", name="sel_admin_api_archivo_create", options={"expose" = true})
     */
    public function create(Request $request, ValidatorInterface $validator, FileManager $fileManager)
    {
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('file');

        $errors = $validator->validate($imageFile, [
            new NotBlank()
        ]);

        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('file');
        $newFilename = $fileManager->uploadFile($imageFile);

        return $this->json(['ok' => $newFilename]);
    }
}