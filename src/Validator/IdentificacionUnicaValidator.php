<?php

namespace App\Validator;

use App\Repository\UsuarioRepository;
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

    public function validate($value, Constraint $constraint)
    {
        $identificacion = is_object($value) ? $value->identificacion : $value;
        $existingUser = $this->usuarioRepository->findOneBy(['identificacion' => $identificacion]);

        if(is_object($value) && $existingUser->getId() === $value->id) {
            return null;
        }
        if(!$existingUser) {
            return null;
        }
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
