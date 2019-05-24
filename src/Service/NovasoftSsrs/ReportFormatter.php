<?php
/**
 * Functions related to convert csv report data to arrays or objects
 * User: guerrerojosedario
 * Date: 2018/08/16
 * Time: 7:34 PM
 */

namespace App\Service\NovasoftSsrs;

use App\Service\Utils;
use App\Service\NovasoftSsrs\Mapper\Mapper;

class ReportFormatter
{
    /**
     * @var Utils
     */
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
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
     * Converts each row of csv string or csv array parsed to a new object of $class
     * @param string|array $csv_data
     * @param string $class
     * @return object[]
     */
    public function csvToObject($csv_data, $class)
    {
        $objects   = [];
        $csv_array = is_array($csv_data) ? $csv_data : $this->csvToAssociative($csv_data);
        $col_setmethod_map = []; //guarda el nombre del metodo set para cada columna, para cache
        $col_camelname_map = [];
        $rows_count = count($csv_array);

        for($i = 0; $i < $rows_count; $i++) {
            $object = new $class();
            foreach($csv_array[$i] as $col_name => $value) {
                // primera iteración guarda el respectivo atributo camelCase y la funcion set
                // para utilizarlos en las demas iteraciones
                if($i == 0) {
                    $col_camelname_map[$col_name] = $this->utils->convertToCamelCase($col_name);
                    $set_method = "set" . ucfirst($col_camelname_map[$col_name]);
                    if(method_exists($object, $set_method)) {
                        $col_setmethod_map[$col_name] = $set_method;
                    }
                }
                // si el objeto tiene la funcion set
                if(isset($col_setmethod_map[$col_name])) {
                    $set_method = $col_setmethod_map[$col_name];
                    $object->$set_method($value);
                }
                // si no (ej stdClass) guarda el atributo
                else {
                    $camelname = $col_camelname_map[$col_name];
                    $object->$camelname = $value;
                }
            }
            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * Converts each row of $csv_data to a object using the Mapper mechanism
     * @param string|array $csv_data
     * @param Mapper $mapper
     * @return mixed array of objects of type the Mapper $targetObject
     */
    public function mapCsv($csv_data, Mapper $mapper)
    {
        $csv_array  = is_array($csv_data) ? $csv_data : $this->csvToAssociative($csv_data);
        $objects    = [];
        $rows_count = count($csv_array);

        for($i = 0; $i < $rows_count; $i++) {
            foreach($csv_array[$i] as $attribute => $value) {
                $mapper->$attribute = $value;
            }
            $mapper->addMappedObject($objects);
        }
        return $objects;
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
}