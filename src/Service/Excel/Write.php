<?php

namespace App\Service\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Write
{
    /**
     * @var string
     */
    private $filePath;
    /**
     * @var Spreadsheet
     */
    private $_spreadsheet;

    private $_writer;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function setValue($value, $columnIndex, $row = null): self
    {
        if ($row) {
            $this->spreadsheet()->getActiveSheet()->setCellValueByColumnAndRow($columnIndex, $row, $value);
        } else {
            $this->spreadsheet()->getActiveSheet()->setCellValue($columnIndex, $value);
        }
        return $this;
    }

    public function save(): self
    {
        $this->writer()->save($this->filePath);
        return $this;
    }

    public function spreadsheet(): Spreadsheet
    {
        return $this->_spreadsheet === null ? $this->_spreadsheet = new Spreadsheet() : $this->_spreadsheet;
    }

    private function writer(): Xlsx
    {
        return $this->_writer === null ? $this->_writer = new Xlsx($this->spreadsheet()) : $this->_writer;
    }
}