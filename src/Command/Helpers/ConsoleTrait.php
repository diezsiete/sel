<?php


namespace App\Command\Helpers;


use App\Entity\Usuario;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;


trait ConsoleTrait
{
    private $superAdmin = null;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @required
     */
    public function setContainer(EntityManagerInterface $em)
    {
        $this->em = $em;
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