<?php
/** @noinspection MissingService */


namespace App\Service\Configuracion;


use App\Constant\HvConstant;
use App\Service\Configuracion\Novasoft\NovasoftApiConfiguracion;
use App\Service\Configuracion\Scraper\ScraperConfiguracion;
use App\Service\Configuracion\ServicioEmpleados\ServicioEmpleados;
use App\Service\Hv\HvWizard\HvWizardRoute;
use Exception;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Twig\Environment;

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
     * @var ScraperConfiguracion
     */
    private $scraper = null;

    /**
     * @var Ael
     */
    private $ael;

    /**
     * @var NovasoftApiConfiguracion
     */
    private $novasoftApiConfig = null;
    /**
     * @deprecated
     */
    private $documentosLaborales = [];

    /**
     * @var SelRoutes
     */
    private $selRoutes = null;

    /**
     * @var Compania[]
     * @deprecated
     */
    private $companias = [];

    /**
     * @var Compania[]
     */
    private $companias2 = [];

    /**
     * @var Compania
     */
    private $companiaDefault;
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var AssetExtension
     */
    private $assetExtension;

    /**
     * @var string
     */
    private $kernelProjectDir;

    /**
     * @var ServicioEmpleados
     */
    private $servicioEmpleadosConfig = null;


    public function __construct(ContainerBagInterface $bag, $webDir, Packages $packages, Environment $twig, $kernelProjectDir)
    {
        $this->bag = $bag;
        $this->empresa = $bag->get('empresa');
        $this->parameters = $bag->get('empresa.'.$this->empresa.'.config');

        $this->webDir = substr($webDir, -1) === "/" ? substr($webDir, 0, strlen($webDir) - 1) : $webDir;

        $this->packages = $packages;
        $this->assetExtension = $twig->getExtension(AssetExtension::class);
        $this->kernelProjectDir = $kernelProjectDir;
    }

    /**
     * @param bool|string $filter
     * @return SsrsDb[]|SsrsDb
     * @throws Exception
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
                throw new Exception("Base de datos " . $filter . " no existe");
            }
            return $return;
        } else {
            return $filter ? $this->ssrsDbs[0] : $this->ssrsDbs;
        }
    }

    public function getEmpresa($lowerCased = false)
    {
        return $lowerCased ? strtolower($this->empresa) : $this->empresa;
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
        return $this->webDir . $this->packages->getUrl($this->parameters['logo']);
    }

    public function getLogoPdf()
    {
        return $this->kernelProjectDir . '/public' . $this->packages->getUrl($this->parameters['logo_pdf']);
    }

    public function getPdfResourcePath($resource)
    {
        return $this->kernelProjectDir . '/public' . $this->packages->getUrl($resource);
    }

    public function homeRoute()
    {
        return $this->parameters['home_route'];
    }

    public function phpExec()
    {
        return $this->parameters['php_exec'];
    }

    public function certificadoLaboral(): CertificadoLaboral
    {
        if(!$this->certificadoLaboral) {
            $data = $this->parameters['certificado_laboral'];
            $this->certificadoLaboral = new CertificadoLaboral(
                $this->kernelProjectDir . '/public' . $this->packages->getUrl($data['firma']),
                $data['firmante'],
                $data['firmante_cargo'],
                $data['firmante_contacto']
            );
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

    /**
     * @return HvWizardRoute[]
     */
    public function getHvWizardRoutes()
    {
        if(!$this->hvWizardRoutes) {
            $this->hvWizardRoutes = [];
            foreach($this->bag->get('hv_wizard_routes') as $key => $routeData) {
                $this->hvWizardRoutes[] = new HvWizardRoute($key, $routeData['route'], $routeData['titulo']);
            }
        }
        return $this->hvWizardRoutes;
    }

    /**
     * @return ScraperConfiguracion
     */
    public function getScraper()
    {
        if(!$this->scraper) {
            $this->scraper = new ScraperConfiguracion($this->bag->get('scraper'), $this->parameters['scraper']);
        }
        return $this->scraper;
    }

    /**
     * @return Ael
     */
    public function ael()
    {
        if(!$this->ael) {
            $this->ael = new Ael($this->bag->get('ael'));
        }
        return $this->ael;
    }

    public function napi(): NovasoftApiConfiguracion
    {
        if(!$this->novasoftApiConfig) {
            $this->novasoftApiConfig = new NovasoftApiConfiguracion($this->bag->get('novasoftapi'), $this->parameters['novasoftapi']);
        }
        return $this->novasoftApiConfig;
    }

    /**
     * @param null|string $searchKey
     * @return DocumentoLaboral[]|DocumentoLaboral
     * @throws Exception
     * @deprecated utilizar DocumentosLaborales
     */
    public function getDocumentosLaborales($searchKey = null)
    {
        if(!$this->documentosLaborales) {
            foreach($this->parameters['documentos_laborales'] as $key => $documentoLaboralData) {
                $documentoLaboralData['key'] = $key;
                $this->documentosLaborales[$key] = new DocumentoLaboral($this->empresa, $documentoLaboralData);
            }
        }
        if($searchKey) {
            if(isset($this->documentosLaborales[$searchKey])) {
                return $this->documentosLaborales[$searchKey];
            } else {
                throw new Exception("documento laboral '$key' doesn't exists'");
            }
        }
        return $this->documentosLaborales;
    }

    public function getSelRoutes()
    {
        if(!$this->selRoutes) {
            $this->selRoutes = new SelRoutes($this->bag->get('sel_routes'));
        }
        return $this->selRoutes;
    }


    /**
     * @param $name
     * @return Compania|mixed
     * @deprecated mal hecho genera confusion con las companias de servilabor, user getCompanias
     */
    public function getCompania($name)
    {
        if(!isset($this->companias[$name])) {
            if(isset($this->parameters['companias'][$name])) {
                //Companias como universal a su logo le agrega el path absoluto al logo pdf
                $data = array_merge($this->parameters['companias'][$name], [
                    'logo_pdf' => $this->kernelProjectDir . '/public' . $this->packages->getUrl($this->parameters['companias'][$name]['logo_pdf'])
                ]);
                $compania = new Compania($data);
            } else {
                //si no existe retornamos default
                if(!$this->companiaDefault) {
                    $this->companiaDefault = new Compania(
                        $this->getRazon(),
                        $this->getNit(),
                        $this->getDir(),
                        $this->getTel(),
                        $this->getWeb(),
                        $this->getLogoPdf()
                    );
                }
                $compania = $this->companiaDefault;
            }
            $this->companias[$name] = $compania;
        }
        return $this->companias[$name];
    }

    public function getCompanias()
    {
        if(!$this->companias2 && isset($this->parameters['companias'])) {
            foreach($this->parameters['companias'] as $companiaName => $config) {
                $config['logo_pdf'] = $this->kernelProjectDir . '/public' . $this->packages->getUrl($config['logo_pdf']);
                $this->companias2[$companiaName] = new Compania($config);
            }
        }
        return $this->companias2;
    }


    public function getHvReferenciaTipo($flipped = true)
    {
        $tiposReferencia = array_flip(HvConstant::REFERENCIA_TIPO);
        //fix para servilabor en tipo de referencia que tiene id diferente
        if($this->getEmpresa(true) === 'servilabor') {
            $tiposReferencia = array_map(function ($id) {
                return $id + 6;
            }, $tiposReferencia);
        }
        return $flipped ? $tiposReferencia : array_flip($tiposReferencia);
    }

    /**
     * @return string
     */
    public function getWebDir(): string
    {
        return $this->webDir;
    }

    /**
     * @return string
     */
    public function getKernelProjectDir(): string
    {
        return $this->kernelProjectDir;
    }

    public function servicioEmpleados(): ServicioEmpleados
    {
        if($this->servicioEmpleadosConfig === null) {
            $this->servicioEmpleadosConfig = new ServicioEmpleados($this->bag->get('se'));
        }
        return $this->servicioEmpleadosConfig;
    }
}