<?php


namespace App\Form\DataTransformer;


use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class AceptoTerminosEnTransformer implements DataTransformerInterface
{

    public function transform($value)
    {
        if($value === null) {
            return false;
        }
        return true;
    }

    public function reverseTransform($value)
    {
        if(!$value) {
            throw new TransformationFailedException("Debe aceptar los terminos");
        }
        return new \DateTime();
    }
}