<?php


namespace App\Entity\Evaluacion\Pregunta;

use App\Repository\Evaluacion\Pregunta\OpcionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleUnica
 * @package App\Entity\Evaluacion\Pregunta
 * @ORM\Entity
 */
class MultipleUnicaIndexed extends MultipleUnica
{

}