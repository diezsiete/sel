<?php


namespace App\Entity\Evaluacion\Respuesta;

use App\Entity\Evaluacion\Pregunta\MultipleUnica as PreguntaMultipleUnica;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MultipleUnicaIndexed
 * @package App\Entity\Evaluacion\Respuesta
 * @ORM\Entity
 * @method PreguntaMultipleUnica getPregunta()
 */
class MultipleUnicaIndexed extends MultipleUnica
{

}