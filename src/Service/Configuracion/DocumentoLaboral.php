<?php


namespace App\Service\Configuracion;


use DateTime;

/**
 * Class DocumentoLaboral
 * @package App\Service\Configuracion
 * @deprecated utilizar DocumentoLaboral de DocumentosLaborales
 */
class DocumentoLaboral
{
    private $empresa;
    private $key;
    private $date;
    private $title;
    private $pdf;

    public function __construct($empresa, $data)
    {
        $this->empresa = strtolower($empresa);
        $this->key = $data['key'];
        $this->date = (new DateTime())->setTimestamp($data['date']);
        $this->title = $data['title'];
        $this->pdf = ($data['pdf'] ? $data['pdf'] : $data['key']) . '.pdf';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getPdf(): string
    {
        return $this->pdf;
    }

    public function getFilePath(): string
    {
        return '/documentos_laborales/'.$this->empresa.'/'.$this->getPdf();
    }

    public function getMimeType(): string
    {
        return "application/pdf";
    }


}