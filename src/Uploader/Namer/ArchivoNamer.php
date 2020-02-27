<?php


namespace App\Uploader\Namer;


use App\Repository\Main\UsuarioRepository;
use App\Service\Utils\Solicitud;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\Naming\NamerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArchivoNamer implements NamerInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Solicitud
     */
    private $solicitudUtil;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepo;

    public function __construct(RequestStack $requestStack, Solicitud $solicitudUtil, UsuarioRepository $usuarioRepo)
    {
        $this->requestStack = $requestStack;
        $this->solicitudUtil = $solicitudUtil;
        $this->usuarioRepo = $usuarioRepo;
    }

    /**
     * @inheritDoc
     */
    public function name(FileInterface $file)
    {
        $owner = $this->solicitudUtil->jsonPostParseBody($this->requestStack->getMasterRequest())->request->get('owner');
        $usuario = $this->usuarioRepo->find($owner);
        return  sprintf('archivo/%s/%s.%s', $usuario->getIdentificacion(), uniqid('', true), $file->getExtension());
    }
}