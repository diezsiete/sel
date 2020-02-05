<?php


namespace App\Controller\Admin\Api;


use App\Controller\BaseController;
use App\Entity\Archivo\Archivo;
use App\Entity\Main\Usuario;
use App\Exception\UploadedFileValidationErrorsException;
use App\Repository\Archivo\ArchivoRepository;
use App\Service\File\ArchivoManager;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArchivoController extends BaseController
{
    /**
     * @Route("/sel/admin/api/{usuario}/archivo/list", methods="GET", name="sel_admin_api_archivo_list", options={"expose" = true})
     */
    public function list(ArchivoRepository $archivoRepository, ?Usuario $usuario = null)
    {
        if(!$usuario) {
            //$usuario = $this->getUser();
            $usuario = $this->getSuperAdmin();
        }

        return $this->json($archivoRepository->findAllByOwner($usuario), 200, [], ['groups' => ['api']]);
    }

    //TODO este debe ser PUT
    /**
     * @Route("/sel/admin/api/{usuario}/archivo",
     *     methods="POST",
     *     name="sel_admin_api_archivo_create",
     *     options={"expose" = true}
     * )
     */
    public function create(Request $request, ArchivoManager $archivoManager, Usuario $usuario)
    {
        /** @var UploadedFile[] $file */
        $files = $request->files->get('file');
        if(!is_array($files)) {
            $files = [$files];
        }
        $archivos = [];
        foreach($files as $file) {
            try {
                $archivos[] = $archivoManager->uploadArchivo($file, $usuario);
            } catch (UploadedFileValidationErrorsException $e) {
                return $this->json($e->getErrors(), 400);
            } catch (Exception $e) {
                return $this->json(['detail' => $e->getMessage()], 400);
            }
        }
        return $this->json($archivos, 200, [], ['groups' => ['api']]);
    }

    /**
     * @Route("/sel/admin/api/{usuario}/archivo", methods="DELETE", name="sel_admin_api_archivo_delete", options={"expose" = true})
     */
    public function delete(ArchivoManager $archivoManager, Request $request, ?Usuario $usuario = null)
    {
        if(!$usuario) {
            //$usuario = $this->getUser();
            $usuario = $this->getSuperAdmin();
        }
        $ok = $archivoManager->deleteById($request->query->get('ids'));

        return $this->json(['ok' => $ok]);
    }

    /**
     * @Route(
     *     "/sel/admin/api/{usuario}/archivo/{archivo}",
     *     methods="GET",
     *     name="sel_admin_api_archivo_view",
     *     options={"expose" = true},
     *     requirements={"archivo"="\d+"}
     * )
     */
    public function view(ArchivoManager $archivoManager, Archivo $archivo)
    {
        return new RedirectResponse($archivoManager->generateLink($archivo));
    }

    /**
     * @Route("/sel/admin/api/{usuario}/archivo/{archivo}/{originalFilename}",
     *     methods="POST",
     *     name="sel_admin_api_archivo_update_original_filename",
     *     options={"expose" = true},
     *     requirements={"archivo"="\d+", "originalFilename"=".+"}
     * )
     */
    public function update(ArchivoManager $archivoManager, Archivo $archivo, $originalFilename)
    {
        try {
            //TODO test poner $originalFilename vacio y mirar error
            $archivo = $archivoManager->updateOriginalFilename($archivo, urldecode($originalFilename));
            return $this->json($archivo, 200, [], ['groups' => ['api']]);
        } catch (UploadedFileValidationErrorsException $e) {
            return $this->json($e->getErrors(), 400);
        } catch (Exception $e) {
            return $this->json(['detail' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/sel/admin/api/{usuario}/archivo/download",
     *     methods="POST",
     *     name="sel_admin_api_archivo_download",
     *     options={"expose" = true},
     * )
     */
    public function download(ArchivoManager $archivoManager, Usuario $usuario, Request $request)
    {
        $archivosIds = $this->jsonPostParseBody($request)->request->get('archivos');
        $url = "";
        if($archivosIds) {
            if(count($archivosIds) === 1) {
                $url = $archivoManager->generateLink((int)$archivosIds[0]);
            } else {
                $archivoManager->downloadLocalClearDirectory($usuario);
                foreach($archivosIds as $archivoId) {
                    $archivoManager->downloadLocal((int)$archivoId);
                }
                $archivoManager->downloadLocalZip($usuario);
                $url = $this->generateUrl('sel_admin_api_archivo_download_zip', [
                    'usuario' => $usuario->getId(),
                ]);
            }
        }
        return $this->json(['url' => $url]);
    }

    /**
     * @Route("/sel/admin/api/{usuario}/archivo/download/zip",
     *     methods="GET",
     *     name="sel_admin_api_archivo_download_zip"
     * )
     */
    public function downloadZip(ArchivoManager $archivoManager, Usuario $usuario)
    {
        return $this->renderZip($archivoManager->downloadLocalZipPath($usuario), $usuario->getIdentificacion() . ".zip");
    }
}