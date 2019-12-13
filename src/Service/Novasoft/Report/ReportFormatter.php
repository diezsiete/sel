<?php
/**
 * Functions related to convert csv report data to arrays or objects
 * User: guerrerojosedario
 * Date: 2018/08/16
 * Time: 7:34 PM
 */

namespace App\Service\Novasoft\Report;

use App\Service\Novasoft\Report\Exception\InvalidMappedObject;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\ConvenioMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\EmpleadoMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\RenglonMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\ResumenMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\ResumenRenglonMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\ResumenTotalMapper;
use App\Service\Novasoft\Report\Mapper\LiquidacionNomina\TotalMapper;
use App\Service\Utils;
use App\Service\Novasoft\Report\Mapper\Mapper;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ReportFormatter implements ServiceSubscriberInterface
{
    /**
     * @var Utils
     */
    private $utils;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->utils = $utils;
        $this->container = $container;
    }

    /**
     * Converts a csv string to a associative array
     * @param $csv_data
     * @return array
     */
    public function csvToAssociative($csv_data)
    {
        $csv   = [];
        $cols  = [];
        $lines = explode("\n", $csv_data);
        $count_lines = count($lines);
        $cols_count = 0;
        for($i = 0; $i < $count_lines; $i++) {
            $row = str_getcsv($lines[$i]);
            if(!$row[0])
                continue;
            // get the columns to assign them to the rows
            if(!$cols) {
                $cols = $this->csvRowToCols($row);
                $cols_count = count($cols);
            }
            //rows
            else if($cols_count == count($row)) {
                $row = array_combine($cols, $row);
                $csv[] = array_map('trim', $row);
            }
        }
        return $csv;
    }

    /**
     * Utilizar para reportes de una sola linea, donde las columnas del csv viene separado en varias lineas como Nom932
     * @param string $csvData
     * @return array|bool|false
     */
    public function csvColsSplittedToAssociative($csvData)
    {
        $cols =  $data = [];
        $lines = explode("\n", $csvData);
        //one_row false, por que el reporte utiliza el procedimiento rs_rhh_RepNom922 que puede botar mas de una linea
        $col = $one_row = false;
        foreach($lines as $line){
            $row = str_getcsv($line);
            if(!$row[0]) {
                $col = $one_row = false;
                continue;
            }
            if(!$col){
                $cols = array_merge($cols, $row);
                $col = true;
            }elseif(!$one_row){
                $data = array_merge($data, $row);
                $one_row = true;
            }
        }
        $csv = false;
        if($data && count($cols) == count($data))
            $csv = array_combine($cols, $data);
        return $csv;
    }

    /**
     * Utilizar para reportes de una sola linea, donde las columnas vienen en primera fila y el contenido en varias
     * @param string $csvData
     * @return array|bool|false
     */
    public function csvContSplittedToAssociative($csvData)
    {
        $cols =  $data = [];
        $lines = explode("\n", $csvData);
        $col = false;
        foreach($lines as $line){
            $row = str_getcsv($line);
            if(!$row[0] && ($col && count($row) < count($cols))) {
                continue;
            }
            if(!$col){
                $cols = array_merge($cols, $row);
                $col = true;
            }else {
                $row = array_combine($cols, $row);
                $data = $data + array_filter($row);
            }
        }
        return $data;
    }

    /**
     * Converts each row of $csv_data to a object using the Mapper mechanism
     * @param string|array $csvData
     * @param Mapper $mapper
     * @return mixed array of objects of type the Mapper $targetObject
     */
    public function mapCsv($csvData, Mapper $mapper, $single = false)
    {
        if(is_array($csvData)) {
            //$csvData puede venir como una array sencilla
            if(!is_array(reset($csvData))) {
                $csvArray = [$csvData];
            } else {
                $csvArray = $csvData;
            }
        } else {
            $csvArray = $this->csvToAssociative($csvData);
        }
        $rowsCount = count($csvArray);

        $objects    = [];
        for($i = 0; $i < $rowsCount; $i++) {
            try {
                foreach($mapper->getMap() as $csvAttribute => $entityAttribute) {
                    if(is_array($entityAttribute)) {
                        $innerMapperClass = array_key_first($entityAttribute);
                        $innerMapperColsFilter = $entityAttribute[$innerMapperClass];
                        $innerMapperCsvData = array_intersect_key($csvArray[$i], array_flip($innerMapperColsFilter));

                        $mapper->$csvAttribute = $this->mapCsv($innerMapperCsvData, $this->container->get($innerMapperClass), true);
                    }

                    else {
                        if (isset($csvArray[$i][$csvAttribute])) {
                            $mapper->$csvAttribute = trim($csvArray[$i][$csvAttribute]);
                        }
                    }
                }
                $mapper->addMappedObject($objects);
            } catch (InvalidMappedObject $e) {
            }
        }
        return $single && count($objects) === 1 ? $objects[0] : $objects;
    }



    /**
     * Takes a row of csv that are column names. If it finds columns repeated, adds appendix
     * @param array $row
     * @return array
     */
    private function csvRowToCols($row)
    {
        $cols_count = count($row);

        //validar que no hallan cols repetidas, se agrega apendice(xx_1) a aquellas que si
        $cols = [];
        $count_repetidas = array_combine($row, array_fill(0, $cols_count, 1));

        for($i = 0; $i < $cols_count; $i++){
            $col = $row[$i];
            if(in_array($col, $cols)) {
                $col = $col . "_" . $count_repetidas[$col];
                $count_repetidas[$row[$i]]++;
            }
            $cols[] = trim(strtolower($col));
        }

        return $cols;
    }


    public static function getSubscribedServices()
    {
        return [
            ConvenioMapper::class,
            EmpleadoMapper::class,
            RenglonMapper::class,
            TotalMapper::class,
            ResumenMapper::class,
            ResumenRenglonMapper::class,
            ResumenTotalMapper::class
        ];
    }
}