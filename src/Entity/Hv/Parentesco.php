<?php


namespace App\Entity\Hv;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Parentesco
 * @package App\Entity\Hv
 * @ApiResource(
 *     collectionOperations={
 *         "get" = {"path": "/parentescos"},
 *     },
 *     itemOperations={
 *         "get" = {"path": "/parentesco/{id}"},
 *     },
 *     attributes={"pagination_enabled"=false}
 * )
 * @ORM\Entity()
 */
class Parentesco
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=2)
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