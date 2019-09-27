<?php


namespace App\Service\Configuracion;


class Compania
{
    private $razon;
    private $nit;
    private $dir;
    private $tel;
    private $web;
    private $logoPdf;

    public function __construct($dataORazon, $nit = null, $dir = null, $tel = null, $web = null, $logoPdf = null)
    {
        if(is_array($dataORazon)) {
            $data = $dataORazon;
        } else {
            $data = [
                'razon' => $dataORazon,
                'nit' => $nit,
                'dir' => $dir,
                'tel' => $tel,
                'web' => $web,
                'logo_pdf' => $logoPdf
            ];
        }
        $this->razon = $data['razon'];
        $this->nit = $data['nit'];
        $this->dir = $data['dir'];
        $this->tel = $data['tel'];
        $this->web = $data['web'];
        $this->logoPdf = $data['logo_pdf'];
    }

    /**
     * @return mixed
     */
    public function getRazon()
    {
        return $this->razon;
    }

    /**
     * @return mixed
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @return mixed
     */
    public function getLogoPdf()
    {
        return $this->logoPdf;
    }
}