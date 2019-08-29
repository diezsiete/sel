<?php

namespace App\Validator;

use App\Entity\Evaluacion\Respuesta\MultipleOrdenar;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EvaluacionMultipleOrdenarValidator extends ConstraintValidator
{
    /**
     * @param MultipleOrdenar $value
     * @param EvaluacionMultipleOrdenar|Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }
        $opcionesSetted = [];
        foreach($value->getOpciones() as $opcion) {
            $preguntaOpcionId = $opcion->getPreguntaOpcion()->getId();
            if(!in_array($preguntaOpcionId, $opcionesSetted)) {
                $opcionesSetted[] = $preguntaOpcionId;
            } else {
                $this->context->buildViolation($constraint->message)
                    // ->setParameter('{{ value }}', $value)
                    ->addViolation();
                break;
            }
        }
    }
}
