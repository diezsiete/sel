<?php


namespace App\Service\Cv;


use ApiPlatform\Core\Api\IriConverterInterface;
use App\Repository\Hv\HvRepository;
use Symfony\Component\Security\Core\Security;

class CvService
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var HvRepository
     */
    private $cvRepo;
    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    public function __construct(Security $security, HvRepository $hvRepository, IriConverterInterface $iriConverter)
    {
        $this->security = $security;
        $this->cvRepo = $hvRepository;
        $this->iriConverter = $iriConverter;
    }

    public function getUserCv()
    {
        if($usuario = $this->security->getUser()) {
            return $this->cvRepo->findByUsuario($usuario);
        }
        return null;
    }

    public function getUserCvIri()
    {
        if($cv = $this->getUserCv()) {
            return $this->iriConverter->getIriFromItem($cv);
        }
        return null;
    }
}