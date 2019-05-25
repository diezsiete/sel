<?php
/**
 * Maps a record from Report NOM936 to Object Convenio
 * User: guerrerojosedario
 * Date: 2018/08/20
 * Time: 11:40 AM
 */

namespace App\Service\NovasoftSsrs\Mapper;


use App\Entity\Convenio;



class MapperNom936 extends Mapper
{

    protected function defineTargetClass(): string
    {
        return Convenio::class;
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
}