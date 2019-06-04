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
        $existingUser = $this->usuarioRepository->findOneBy(['identificacion' => $value]);
        if(!$existingUser) {
            return null;
        }
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
