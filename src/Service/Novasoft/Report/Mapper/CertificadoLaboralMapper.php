<?php


namespace App\Service\Novasoft\Report\Mapper;


use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\CertificadoLaboral;
use App\Repository\Main\UsuarioRepository;
use DateTime;

class CertificadoLaboralMapper extends Mapper
{
    /**
     * @var CertificadoLaboral
     */
    protected $targetObject;
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    public function __construct(DataFilter $filter, UsuarioRepository $usuarioRepository)
    {
        parent::__construct($filter);
        $this->usuarioRepository = $usuarioRepository;
    }

    protected function instanceTargetObject()
    {
        return new CertificadoLaboral();
    }

    protected function defineMap(): array
    {
        return [
            'nom_emp' => 'nombre',
            'ap1_emp' => 'primerApellido',
            'ap2_emp' => 'segundoApellido',
            'est_lab' => 'activo',
            'e_mail' => 'email',
            'cod_emp' => 'cedula',
            'nom_con' => 'contrato',
            'nombre' => 'empresaUsuaria',
            'nom_car' => 'cargo',
            'nSalario' => 'nsalario',
            'sal_bas' => 'salario',
            'sex_emp' => 'esHombre',
            'fecing' => 'fechaIngreso',
            'fec_egr' => 'fechaEgreso',
            'fec_ing1' => 'fechaIngresoTextual',
            'Fcert' => 'fechaCertificadoTextual'
        ];
    }

    public function setActivo($activo)
    {
        $this->targetObject->setActivo($activo != 99);
    }

    public function setSalario($salario)
    {
        $this->targetObject->setSalario(str_replace(',','.', number_format((float)trim($salario))));
    }

    public function setEsHombre($esHombre)
    {
        $this->targetObject->setHombre($esHombre != 1);
    }

    public function setFechaIngreso($fechaIngreso)
    {
        $fechaIngreso = trim(str_replace('0:00:00', '', trim($fechaIngreso)));
        $this->targetObject->setFechaIngreso(DateTime::createFromFormat('d/m/Y', $fechaIngreso));
    }

    public function setFechaEgreso($fechaEgreso)
    {
        $fechaEgreso = trim(str_replace('0:00:00', '', trim($fechaEgreso)));
        if($fechaEgreso) {
            $fechaEgreso = DateTime::createFromFormat('d/m/Y', $fechaEgreso);
        } else {
            $fechaEgreso = null;
        }
        $this->targetObject->setFechaEgreso($fechaEgreso);
    }

    public function setCedula($cedula)
    {
        $identificacion = preg_replace('/\D/', '', $cedula);
        $this->targetObject->setCedula($cedula);
        $usuario = $this->usuarioRepository->findByIdentificacion($identificacion);
        if(!$usuario) {
            $usuario = (new Usuario())
                ->setPrimerNombre($this->targetObject->getNombre())
                ->setPrimerApellido($this->targetObject->getPrimerApellido())
                ->setSegundoApellido($this->targetObject->getSegundoApellido())
                ->setEmail($this->targetObject->getEmail());
        }
        $this->targetObject->setUsuario($usuario);

    }
}