<?php


namespace App\Controller;


use App\Entity\Hv;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HvAdjuntoController extends BaseController
{
    /**
     * @Route("/sel/hv-adjunto/{id}/upload", name="hv_adjunto_upload", methods={"POST"})
     */
    public function upload(Hv $hv, Request $request)
    {
        dd($request->files->get('adjunto'));
    }
}