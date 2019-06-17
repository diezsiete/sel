<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SelParameters
{
    /**
     * @var ContainerBagInterface
     */
    private $bag;

    private $parameters = [];

    /**
     * @var string
     */
    private $webDir;

    public function __construct(ContainerBagInterface $bag, $webDir)
    {
        $this->parameters = $bag->get('empresa.config');
        $this->webDir = $webDir;
    }

    public function getRazon()
    {
        return $this->parameters['razon'];
    }

    public function getNit()
    {
        return $this->parameters['nit'];
    }

    public function getDir()
    {
        return $this->parameters['dir'];
    }

    public function getWeb()
    {
        return $this->parameters['web'];
    }

    public function getMail()
    {
        return $this->parameters['mail'];
    }

    public function getLogo()
    {
        return $this->parameters['logo'];
    }

    public function getLogoPdf()
    {
        return $this->webDir . $this->parameters['logo_pdf'];
    }

    public function getCertificadoLaboralFirma()
    {
        return $this->webDir . $this->parameters['certificado_laboral']['firma'];
    }

    public function getCertificadoLaboralFirmante()
    {
        return $this->parameters['certificado_laboral']['firmante'];
    }

    public function getCertificadoLaboralFirmanteCargo()
    {
        return $this->parameters['certificado_laboral']['firmante_cargo'];
    }

    public function getCertificadoLaboralFirmanteContacto()
    {
        return $this->parameters['certificado_laboral']['firmante_contacto'];
    }


    public function getTemplateLogin()
    {
        return $this->parameters['templates']['login'];
    }
}