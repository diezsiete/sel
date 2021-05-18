<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Read
{
    /**
     * @var Spreadsheet
     */
    private $spreadsheet;

    private $trimCharlist = null;

    public function __construct($filePath)
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $this->spreadsheet = $reader->load($filePath);
    }

    public function cell($pCoordinate)
    {
        $cellValue = $this->spreadsheet->getActiveSheet()->getCell($pCoordinate)->getValue();
        return $this->trimCharlist ? trim($cellValue, $this->trimCharlist) : $cellValue;
    }

    public function range($pRange)
    {
        return $this->spreadsheet->getActiveSheet()->rangeToArray($pRange);
    }

    /**
     * @param string $col eg 'A'
     * @return array
     * @throws Exception
     */
    public function col($col)
    {
        $worksheet = $this->spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $columnIndex = $this->columnIndexFromString($col);

        $col = [];

        for ($row = 1; $row <= $highestRow; ++$row) {
            $cellValue = $worksheet->getCellByColumnAndRow($columnIndex, $row)->getValue();
            $col[] = $this->trimCharlist ? trim($cellValue, $this->trimCharlist) : $cellValue;
        }

        return $col;
    }

    /**
     * @param string|int $pRange pude ser el numero de la fila
     * @param array|null $titles
     * @return array|null
     */
    public function row($pRange, ?array $titles = null): ?array
    {
        $worksheet = $this->spreadsheet->getActiveSheet();
        if (is_numeric($pRange)) {
            $highestCol = $worksheet->getHighestColumn($pRange);
            $pRange = "A$pRange:$highestCol$pRange";
        }

        $row = $worksheet->rangeToArray($pRange);
        if (is_array($row) && isset($row[0]) && is_array($row[0])) {
            $row = $row[0];
            if (count($row) === 1 && $row[array_key_first($row)] === null) {
                $row = null;
            } elseif ($titles) {
                $tmpRow = [];
                foreach ($titles as $key => $title) {
                    if (array_key_exists($key, $row)) {
                        $tmpRow[$title] = $row[$key];
                    }
                }
                $row = $tmpRow;
            }
        } else {
            $row = null;
        }
        return $row;
    }

    /**
     * @param string $charlist
     * @return Read
     */
    public function enableTrim($charlist = "")
    {
        $this->trimCharlist = " \t\n\r\0\x0B" . $charlist;
        return $this;
    }

    public function getHighestRow()
    {
        return $this->spreadsheet->getActiveSheet()->getHighestRow();
    }

    public function columnStringFromIndex($colIndex)
    {
        return Coordinate::stringFromColumnIndex($colIndex);
    }

    public function columnIndexFromString($pString)
    {
        return Coordinate::columnIndexFromString($pString);
    }
}