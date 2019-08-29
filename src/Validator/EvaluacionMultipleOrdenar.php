<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class EvaluacionMultipleOrdenar extends Constraint
{
    public $message = 'No se puede repetir opciones';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
