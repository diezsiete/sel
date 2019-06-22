<?php


namespace App\Service\Configuracion;


use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Configuracion
{
    /**
     * @var string
     */
    private $webDir;

    /**
     * @var SsrsDb[]
     */
    private $ssrsDbs = null;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var CertificadoLaboral
     */
    private $certificadoLaboral = null;

    public function __construct(ContainerBagInterface $bag, $webDir)
    {
        $this->parameters = $bag->get('empresa.'.$bag->get('empresa').'.config');
        $this->webDir = $webDir;
    }

    /**
     * @param bool|string $first
     * @return SsrsDb[]|SsrsDb
     * @throws \Exception
     */
    public function getSsrsDb($first = false)
    {
        if(!$this->ssrsDbs) {
            $this->ssrsDbs = [];
            foreach($this->parameters['ssrs_db'] as $ssrsDbNombre => $ssrsDbConfig) {
                $this->ssrsDbs[] = new SsrsDb($ssrsDbNombre, $ssrsDbConfig);
            }
        }

        if(is_string($first)) {
            $return = current(array_filter($this->ssrsDbs, function(SsrsDb $ssrsDb) use ($first) {
                return $ssrsDb->getNombre() === $first;
            }));
            if(!$return) {
                throw new \Exception("Base de datos " . $first . " no existe");
            }
            return $return;
        } else {
            return $first ? $this->ssrsDbs[0] : $this->ssrsDbs;
        }
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

    public function getTel()
    {
        return $this->parameters['tel'];
    }

    public function getLogo()
    {
        return $this->parameters['logo'];
    }

    public function getLogoPdf()
    {
        return $this->webDir . $this->parameters['logo_pdf'];
    }

    public function certificadoLaboral(): CertificadoLaboral
    {
        if(!$this->certificadoLaboral) {
            $this->certificadoLaboral = new CertificadoLaboral(
                $this->parameters['certificado_laboral']['firma'],
                $this->parameters['certificado_laboral']['firmante'],
                $this->parameters['certificado_laboral']['firmante_cargo'],
                $this->parameters['certificado_laboral']['firmante_contacto']);
        }
        return $this->certificadoLaboral;
    }

    /**
     * @return string
     * @deprecated
     */
    public function getTemplateLogin()
    {
        return $this->parameters['templates']['login'];
    }

    public function getContactoEmail()
    {
        return $this->parameters['contacto_email'];
    }
}