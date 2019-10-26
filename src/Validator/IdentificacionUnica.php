<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION", "CLASS"})
 */
class IdentificacionUnica extends Constraint
{
    public $message = 'Identificación ya registrada';
    public $path = '';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }


}
