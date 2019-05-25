<?php


namespace App\Service\NovasoftSsrs\Mapper;


use App\Service\NovasoftSsrs\Entity\NovasoftCertificadoIngresos;

class MapperNom92117 extends Mapper
{
    /**
     * @var NovasoftCertificadoIngresos
     */
    protected $targetObject;

    protected function defineTargetClass(): string
    {
        return NovasoftCertificadoIngresos::class;
    }

    protected function defineMap(): array
    {
        return [
            'textbox164' => 'DV',
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
            'textbox13'  => 'certificoTexto',
            'textbox18'  => 'certificoTexto',
            'textbox26'  => 'certificoTexto',
            'textbox36'  => 'certificoTexto',
            'lit5'       => 'certificoTexto',
            'lit6'       => 'certificoTexto',
            'lit7'       => 'certificoTexto',
            'textbox192' => 'certificoTexto',
        ];
    }
}