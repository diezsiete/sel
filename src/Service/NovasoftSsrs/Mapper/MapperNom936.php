<?php
/**
 * Maps a record from Report NOM936 to Object Convenio
 * User: guerrerojosedario
 * Date: 2018/08/20
 * Time: 11:40 AM
 */

namespace App\Service\NovasoftSsrs\Mapper;


use App\Entity\Main\Convenio;
use App\Service\NovasoftSsrs\Exception\InvalidMappedObject;


class MapperNom936 extends Mapper
{
    /**
     * @var Convenio
     */
    protected $targetObject;

    protected function instanceTargetObject()
    {
        return new Convenio();
    }

    protected function defineMap(): array
    {
        return [
            'cod_conv' => 'codigo',
            'cod_cli'  => 'codigoCliente',
            'nom_conv' => 'nombre',
            'dir_conv' => 'direccion'
        ];
    }

    /**
     * @param $nombre
     * @throws InvalidMappedObject
     */
    public function setNombre($nombre)
    {
        if(strstr($nombre, 'NO USAR') !== false) {
            throw new InvalidMappedObject();
        }
        $this->targetObject->setNombre($nombre);
    }
}