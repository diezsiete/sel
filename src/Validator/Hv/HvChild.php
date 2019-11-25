<?php

namespace App\Validator\Hv;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class HvChild extends Constraint
{

    public $message = 'The child is not valid.';
    public $uniqueFields = [];
    public $rules = [];

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
