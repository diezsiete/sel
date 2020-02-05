<?php

namespace App\Validator\Hv;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Ubicacion extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'Por favor seleccione {{ field }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
