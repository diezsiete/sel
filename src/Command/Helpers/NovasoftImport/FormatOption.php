<?php


namespace App\Command\Helpers\NovasoftImport;


use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use App\Service\Novasoft\Report\Report\Report;
use Exception;
use Symfony\Component\Console\Input\InputOption;

trait FormatOption
{
    private $formats = ['pdf', 'csv', 'db'];
    /**
     * @Configure
     */
    public function addOptionFormat()
    {
        $this->addOption('format', null, InputOption::VALUE_REQUIRED,
            'El formato a importar el reporte ['.implode(', ', $this->formats).']', 'db');
    }

    protected function import(Report $report)
    {
        $format = $this->input->getOption('format');

        if(!in_array($format, $this->formats)) {
            throw new Exception(
                "El formato '{$format}' no es sportado. Unicos formatos son [" . implode(', ', $this->formats) . ']'
            );
        }
        switch($format) {
            case 'pdf':
                $report->getImporter()->importPdf();
                break;
            case 'csv':
            default:
                $report->getImporter()->importMap();
                break;
        }
    }
}