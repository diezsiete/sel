<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class IdentificacionUnica extends Constraint
{
    public $message = 'Identificación ya registrada';
}
