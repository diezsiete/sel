<?php


namespace App\Service\Novasoft\Report\Mapper;


use App\Entity\Main\Usuario;
use App\Entity\Novasoft\Report\CertificadoIngresos;
use App\Repository\Main\UsuarioRepository;
use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use DateTime;

class CertificadoIngresosMapper extends Mapper
{
    /**
     * @var CertificadoIngresos
     */
    protected $targetObject;


    private $periodoCertificacionDe = [];
    private $periodoCertificacionA = [];
    private $fechaExpedicion = [];
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
        return new CertificadoIngresos();
    }

    protected function defineMap(): array
    {
        return [
            'textbox228' => 'usuario',
            'Textbox164' => 'DV',
            'textbox39'  => 'primerApellido',
            'textbox165' => 'segundoApellido',
            'textbox174' => 'nombres',
            'textbox191' => 'periodoCertificacionDe',
            'textbox193' => 'periodoCertificacionDe',
            'textbox194' => 'periodoCertificacionDe',
            'textbox196' => 'periodoCertificacionA',
            'textbox197' => 'periodoCertificacionA',
            'textbox198' => 'periodoCertificacionA',
            'textbox200' => 'fechaExpedicion',
            'textbox201' => 'fechaExpedicion',
            'textbox202' => 'fechaExpedicion',
            'textbox203' => 'lugarRetencion',
            'textbox270' => 'codigoDepartamento',
            'textbox277' => 'codigoDepartamento',
            'textbox284' => 'codigoCiudad',
            'textbox206' => 'codigoCiudad',
            'textbox207' => 'codigoCiudad',
            'textbox49'  => 'pagosSalarios',
            'textbox51'  => 'pagosHonorarios',
            'textbox54'  => 'pagosServicios',
            'textbox57'  => 'pagosComisiones',
            'textbox69'  => 'pagosPrestaciones',
            'textbox72'  => 'pagosViaticos',
            'textbox76'  => 'pagosRepresentacion',
            'textbox77'  => 'pagosCompensaciones',
            'textbox105' => 'pagosOtros',
            'textbox106' => 'pagosCesantias',
            'textbox107' => 'pagosJubilacion',
            'textbox75'  => 'totalIngresosBrutos',
            'textbox80'  => 'aportesSalud',
            'textbox108' => 'aportesPensionObligatorio',
            'textbox71'  => 'aportesPensionVoluntario',
            'textbox122' => 'aportesAFC',
            'textbox65'  => 'valorRetencionFuente',
            'val_var'    => 'pagadorNombre',
            'val_var_1'  => 'pagadorNit',
            'textbox123' => 'totalTexto',
            'textbox28'  => 'total',
            'textbox159' => 'certificoTexto',
            'Textbox13'  => 'certificoTexto',
            'Textbox18'  => 'certificoTexto',
            'Textbox26'  => 'certificoTexto',
            'Textbox36'  => 'certificoTexto',
            'Lit5'       => 'certificoTexto',
            'Lit6'       => 'certificoTexto',
            'Lit7'       => 'certificoTexto',
            'textbox192' => 'certificoTexto',
        ];
    }

    public function setUsuario($cedula)
    {
        $identificacion = preg_replace('/\D/', '', $cedula);
        $usuario = $this->usuarioRepository->findByIdentificacion($identificacion);
        if(!$usuario) {
            throw new InvalidMappedObject("usuario con cedula '$cedula' no existe");
        }
        $this->targetObject->setUsuario($usuario);
    }

    public function setPeriodoCertificacionDe($value)
    {
        $this->periodoCertificacionDe[] = $value;
        if(count($this->periodoCertificacionDe) === 3) {
            $periodoCertificacionDe = DateTime::createFromFormat('Y-m-d', implode('-', $this->periodoCertificacionDe));
            $this->targetObject->setPeriodoCertificacionDe($periodoCertificacionDe);
            $this->periodoCertificacionDe = [];
        }
    }
    public function setPeriodoCertificacionA($value)
    {
        $this->periodoCertificacionA[] = $value;
        if(count($this->periodoCertificacionA) === 3) {
            $periodoCertificacionA = DateTime::createFromFormat('Y-m-d', implode('-', $this->periodoCertificacionA));
            $this->targetObject->setPeriodoCertificacionA($periodoCertificacionA);
            $this->periodoCertificacionA = [];
        }
    }

    public function setFechaExpedicion($value)
    {
        $this->fechaExpedicion[] = $value;
        if(count($this->fechaExpedicion) === 3) {
            $fechaExpedicion = DateTime::createFromFormat('Y-m-d', implode('-', $this->fechaExpedicion));
            $this->targetObject->setFechaExpedicion($fechaExpedicion);
            $this->fechaExpedicion = [];
        }
    }

    public function setCodigoDepartamento($value)
    {
        $this->targetObject->setCodigoDepartamento($this->targetObject->getCodigoDepartamento() . $value);
    }

    public function setCodigoCiudad($value)
    {
        $this->targetObject->setCodigoCiudad($this->targetObject->getCodigoCiudad() . $value);
    }

    public function setCertificoTexto($value)
    {
        $value = ($this->targetObject->getCertificoTexto() ? $this->targetObject->getCertificoTexto() . "\n$value" : $value);
        $this->targetObject->setCertificoTexto($value);
    }
}