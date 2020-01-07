<?php

namespace App\Validator;

use App\Repository\Main\UsuarioRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IdentificacionUnicaValidator extends ConstraintValidator
{
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(UsuarioRepository $userRepository)
    {
        $this->usuarioRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint|IdentificacionUnica $constraint
     * @return null
     */
    public function validate($value, Constraint $constraint)
    {
        $identificacion = is_object($value) ? $value->identificacion : $value;
        $existingUser = $this->usuarioRepository->findOneBy(['identificacion' => $identificacion]);

        if(!$existingUser) {
            return null;
        }
        if(is_object($value) && $existingUser->getId() === $value->id) {
            return null;
        }

        $violationBuilder = $this->context->buildViolation($constraint->message);
        if($constraint->path) {
            $violationBuilder->atPath($constraint->path);
        }
        $violationBuilder->addViolation();
    }
}
