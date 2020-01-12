<?php


namespace App\Service\Novasoft\Report\Importer;


class GenericImporter extends Importer
{

    protected function findEqual($entity)
    {
        // TODO: Implement findEqual() method.
    }

    /**
     * Que hacer si findEqual encontro coincidencia exacta en base de datos
     * @param $equal
     * @return bool determina si importar o no
     */
    protected function handleEqual($equal): bool
    {
        return false;
    }
}