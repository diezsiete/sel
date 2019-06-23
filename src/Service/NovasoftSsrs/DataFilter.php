<?php


namespace App\Service\NovasoftSsrs;


use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataFilter
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function limpiarNombre($nombre)
    {
        $nombre = mb_strtoupper($nombre);
        return preg_replace("/[^A-ZÁÉÍÓÚÑ ]/", "", $nombre);
    }

    public function separarNombre($value)
    {
        $nom_explode = explode(' ', $value);
        $pn = trim($nom_explode[0]);
        $sn = count($nom_explode) > 1 ? trim($nom_explode[1]) : '';
        return [$pn, $sn];
    }

    public function fechaFromNovasoft(string $fecha): ?\DateTime
    {
        $format = preg_match('/\d:\d/', $fecha) ? 'd/m/Y H:i:s' : 'd/m/Y';
        return \DateTime::createFromFormat($format, $fecha);
    }

    public function emailValido(string $email): ?string
    {
        $violations = $this->validator->validate($email, [
            new NotBlank(),
            new Email()
        ]);
        return count($violations) > 0 ? null : $email;
    }
}