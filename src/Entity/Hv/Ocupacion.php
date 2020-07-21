<?php


namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ocupacion
 * @package App\Entity\Hv
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/ocupaciones"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/ocupacion/{id}"},
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class Ocupacion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $nombre;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }
}