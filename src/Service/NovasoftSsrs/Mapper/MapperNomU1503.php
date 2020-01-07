<?php
/**
 * Maps a record from Report NOMU1503 to Object Empleado
 * User: guerrerojosedario
 * Date: 2018/08/20
 * Time: 11:40 AM
 */

namespace App\Service\NovasoftSsrs\Mapper;

use App\Entity\Main\Empleado;
use App\Entity\Main\Usuario;
use App\Repository\Main\ConvenioRepository;

class MapperNomU1503 extends Mapper
{
    /**
     * @var ConvenioRepository
     */
    private $convenioRepository;

    public function __construct(ConvenioRepository $convenioRepository)
    {
        $this->convenioRepository = $convenioRepository;
        parent::__construct();
    }

    /**
     * @var \App\Entity\Main\Empleado
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        $targetObject = new Empleado();
        $targetObject->setUsuario(new Usuario());
        return $targetObject;
    }

    protected function defineMap(): array
    {
        return [
            'identific' => 'identificacion',
            'empleado'  => 'nombre',
            'sexo' => 'sexo',
            'estado_civil' => 'estadoCivil',
            'hijos' => 'hijos',
            'textbox14' => 'nacimiento',
            'tel1' => 'telefono1',
            'tel2' => 'telefono2',
            'direccion' => 'direccion',
            'e_mail' => 'email',
            'cargo' => 'cargo',
            'ccosto' => 'centroCosto',
            'nomina' => 'convenio',
            'fec_ing' => 'fechaIngreso',
            'fec_ret' => 'fechaRetiro'
        ];
    }

    protected function setIdentificacion($identificacion)
    {
        $this->targetObject->getUsuario()->setIdentificacion($identificacion);
    }

    protected function setNombre($nombre)
    {
        $nombreExplode = explode(" ", $nombre);
        $countNombreExplode = count($nombreExplode);
        if($countNombreExplode) {
            switch ($countNombreExplode) {
                case 1 :
                    $this->targetObject->getUsuario()->setPrimerNombre($nombreExplode[0]);
                    $this->targetObject->getUsuario()->setPrimerApellido("SIN DEFINIR");
                    break;
                case 2 :
                    $this->targetObject->getUsuario()->setPrimerApellido($nombreExplode[0]);
                    $this->targetObject->getUsuario()->setPrimerNombre($nombreExplode[1]);
                    break;
                case 4 :
                    $this->targetObject->getUsuario()->setPrimerApellido($nombreExplode[0]);
                    $this->targetObject->getUsuario()->setSegundoApellido($nombreExplode[1]);
                    $this->targetObject->getUsuario()->setPrimerNombre($nombreExplode[2]);
                    $this->targetObject->getUsuario()->setSegundoNombre($nombreExplode[3]);
                    break;
                default:
                    $this->targetObject->getUsuario()->setPrimerApellido($nombreExplode[0]);
                    $this->targetObject->getUsuario()->setSegundoApellido($nombreExplode[1]);
                    $this->targetObject->getUsuario()->setPrimerNombre($nombreExplode[$countNombreExplode - 1]);
            }
        }
    }

    protected function setEmail($email)
    {
        $this->targetObject->getUsuario()->setEmail($email);
    }

    protected function setNacimiento($nacimiento)
    {
        $this->targetObject->setNacimiento(\DateTime::createFromFormat('d/m/Y', $nacimiento));
    }

    protected function setFechaIngreso($fecha_ingreso)
    {
        $this->targetObject->setFechaIngreso(\DateTime::createFromFormat('d/m/Y', $fecha_ingreso));
    }

    protected function setFechaRetiro($fecha_retiro)
    {
        if($fecha_retiro = trim($fecha_retiro)) {
            $this->targetObject->setFechaRetiro(\DateTime::createFromFormat('d/m/Y', $fecha_retiro));
        }
    }

    protected function setTelefono1($tel1)
    {
        if(is_numeric($tel1)) {
            $this->targetObject->setTelefono1($tel1);
        } else {
            $this->targetObject->setTelefono1(0);
        }
    }

    protected function setTelefono2($tel2)
    {
        if($tel2 && is_numeric($tel2)) {
            $this->targetObject->setTelefono2((int)$tel2);
        } else {
            $this->targetObject->setTelefono2(null);
        }
    }

    /**
     * @param $convenio_codigo
     * @throws \Exception
     */
    protected function setConvenio($convenio_codigo)
    {
        $convenio = $this->convenioRepository->find($convenio_codigo);
        $this->targetObject->setConvenio($convenio);
    }

    protected function setSexo($sexo)
    {
        $sexo = $sexo == "M" ? 2 : 1;
        $this->targetObject->setSexo($sexo);
    }
}