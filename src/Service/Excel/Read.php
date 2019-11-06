<?php


namespace App\Service\Excel;


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
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
        return $this->spreadsheet->getActiveSheet()
            ->rangeToArray($pRange, $nullValue = null, $calculateFormulas = true, $formatData = true, $returnCellRef = false);
    }

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
     * @param string $charlist
     * @return Read
     */
    public function enableTrim($charlist = "")
    {
        $this->trimCharlist = " \t\n\r\0\x0B" . $charlist;
        return $this;
    }



    private function columnIndexFromString($pString)
    {
        return Coordinate::columnIndexFromString($pString);
    }
}