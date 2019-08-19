<?php


namespace App\Command\Helpers;


use App\Entity\Usuario;
use App\Service\Configuracion\Configuracion;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

trait SelCommandTrait
{
    /**
     * @var null|Usuario
     */
    private $superAdmin = null;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Configuracion
     */
    protected $configuracion;

    /**
     * @required
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @required
     */
    public function setConfiguracion(Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;
    }

    /**
     * @return Usuario|null
     * @throws NonUniqueResultException
     */
    public function getSuperAdmin($cache = true)
    {
        if(!$this->superAdmin || !$cache) {
            $this->superAdmin = $this->em->getRepository(Usuario::class)->superAdmin();
        }
        return  $this->superAdmin;
    }
}