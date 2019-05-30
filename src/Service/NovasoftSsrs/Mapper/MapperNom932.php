<?php


namespace App\Service\NovasoftSsrs\Mapper;


use App\Service\NovasoftSsrs\Entity\ReporteCertificadoLaboral;

class MapperNom932 extends Mapper
{
    /**
     * @var ReporteCertificadoLaboral
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new ReporteCertificadoLaboral();
    }

    protected function defineMap(): array
    {
        return [
            'nom_emp' => 'nombre',
            'ap1_emp' => 'primerApellido',
            'ap2_emp' => 'segundoApellido',
            'est_lab' => 'activo',
            'cod_emp' => 'cedula',
            'nom_con' => 'contrato',
            'nombre' => 'empresaUsuaria',
            'nom_car' => 'cargo',
            'nSalario' => 'nsalario',
            'sal_bas' => 'salario',
            'sex_emp' => 'esHombre',
            'fecing' => 'fechaIngreso',
            'fec_egr' => 'fechaEgreso',
            'e_mail' => 'email',
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
        $this->targetObject->setFechaIngreso(\DateTime::createFromFormat('m/d/Y', $fechaIngreso));
    }

    public function setFechaEgreso($fechaEgreso)
    {
        $fechaEgreso = trim(str_replace('0:00:00', '', trim($fechaEgreso)));
        if($fechaEgreso) {
            $fechaEgreso = \DateTime::createFromFormat('m/d/Y', $fechaEgreso);
        } else {
            $fechaEgreso = null;
        }
        $this->targetObject->setFechaEgreso($fechaEgreso);
    }
}