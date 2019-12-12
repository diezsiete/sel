<?php


namespace App\Service\Novasoft\Report\Mapper;


use DateTime;
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

    public function separarNombreCompleto($value)
    {
        $nombreCompleto = ["SIN DEFINIR", "", "SIN DEFINIR", ""];

        $nombreExplode = array_map(function($item) { return trim($item); }, explode(" ", $value));
        $countNombreExplode = count($nombreExplode);

        if($countNombreExplode) {
            switch ($countNombreExplode) {
                case 1 :
                    $nombreCompleto[0] = $nombreExplode[0];
                    break;
                case 2 :
                    $nombreCompleto[2] = $nombreExplode[0];
                    $nombreCompleto[0] = $nombreExplode[1];
                    break;
                case 4 :
                    $nombreCompleto[0] = $nombreExplode[2];
                    $nombreCompleto[1] = $nombreExplode[3];
                    $nombreCompleto[2] = $nombreExplode[0];
                    $nombreCompleto[3] = $nombreExplode[1];
                    break;
                default:
                    $nombreCompleto[2] = $nombreExplode[0];
                    $nombreCompleto[3] = $nombreExplode[1];
                    $nombreCompleto[0] = $nombreExplode[$countNombreExplode - 1];
            }
        }
        return $nombreCompleto;
    }

    public function fechaFromNovasoft(string $fecha): ?DateTime
    {
        $format = preg_match('/\d:\d/', $fecha) ? 'd/m/Y H:i:s' : 'd/m/Y';
        $fecha = DateTime::createFromFormat($format, $fecha);
        return $fecha ?? null;
    }

    public function valorMoneda(string $value): int
    {
        $value = str_replace(['.', ','], '', $value);
        return (int)$value;
    }

    public function float($value)
    {
        return (float)str_replace(',', '.', $value);
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