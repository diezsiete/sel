<?php
/** @noinspection MissingService */


namespace App\Service\Configuracion;


use App\Service\Hv\HvWizard\HvWizardRoute;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class Configuracion
{
    /**
     * @var ContainerBagInterface
     */
    private $bag;

    /**
     * @var string
     */
    private $empresa;

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

    /**
     * @var Oficina[]
     */
    private $oficinas = null;

    /**
     * @var Emails
     */
    private $emails = null;

    /**
     * @var HvWizardRoute[]
     */
    private $hvWizardRoutes = null;

    /**
     * @var null
     */
    private $scrapper = null;

    public function __construct(ContainerBagInterface $bag, $webDir)
    {
        $this->bag = $bag;
        $this->empresa = $bag->get('empresa');
        $this->parameters = $bag->get('empresa.'.$this->empresa.'.config');
        $this->webDir = $webDir;
    }

    /**
     * @param bool|string $filter
     * @return SsrsDb[]|SsrsDb
     * @throws \Exception
     */
    public function getSsrsDb($filter = false)
    {
        if(!$this->ssrsDbs) {
            $this->ssrsDbs = [];
            foreach($this->parameters['ssrs_db'] as $ssrsDbNombre => $ssrsDbConfig) {
                $this->ssrsDbs[] = new SsrsDb($ssrsDbNombre, $ssrsDbConfig);
            }
        }

        if(is_string($filter)) {
            $return = current(array_filter($this->ssrsDbs, function(SsrsDb $ssrsDb) use ($filter) {
                return $ssrsDb->getNombre() === $filter;
            }));
            if(!$return) {
                throw new \Exception("Base de datos " . $filter . " no existe");
            }
            return $return;
        } else {
            return $filter ? $this->ssrsDbs[0] : $this->ssrsDbs;
        }
    }

    public function getEmpresa()
    {
        return $this->empresa;
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

    public function homeRoute()
    {
        return $this->parameters['home_route'];
    }

    public function certificadoLaboral(): CertificadoLaboral
    {
        if(!$this->certificadoLaboral) {
            $this->certificadoLaboral = new CertificadoLaboral($this->parameters['certificado_laboral'], $this->webDir);
        }
        return $this->certificadoLaboral;
    }

    public function oficinas($nombreOficina = null)
    {
        if(!$this->oficinas) {
            $this->oficinas = [];
            foreach($this->parameters['oficinas'] as $nombre => $oficinaData) {
                $this->oficinas[$nombre] = new Oficina($nombre, $oficinaData);
            }
        }
        if($nombreOficina) {
            return $this->oficinas[$nombreOficina] ?? null;
        } else {
            return $this->oficinas;
        }
    }

    public function getEmails(): Emails
    {
        if(!$this->emails) {
            $this->emails = new Emails($this->parameters['emails']);
        }
        return $this->emails;
    }

    public function getHvWizardRoutes()
    {
        if(!$this->hvWizardRoutes) {
            $this->hvWizardRoutes = [];
            foreach($this->parameters['hv_wizard_routes'] as $key => $routeData) {
                $this->hvWizardRoutes[] = new HvWizardRoute($key, $routeData['route'], $routeData['titulo']);
            }
        }
        return $this->hvWizardRoutes;
    }

    public function getScrapper()
    {
        if(!$this->scrapper) {
            $this->scrapper = new ScrapperConfiguracion($this->bag->get('scrapper'));
        }
        return $this->scrapper;
    }
}