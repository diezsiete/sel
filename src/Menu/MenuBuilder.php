<?php


namespace App\Menu;


use App\Service\Hv\HvResolver;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

class MenuBuilder
{
    private $factory;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var HvResolver
     */
    private $hvResolver;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, Security $security, HvResolver $hvResolver)
    {
        $this->factory = $factory;
        $this->security = $security;
        $this->hvResolver = $hvResolver;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('main');

        $menu->addChild('Dashboard', ['route' => 'app_main'])
            ->setExtra('icon', 'fas fa-home');

        $user = $this->security->getUser();


        if ($this->security->isGranted(['ROLE_VER_SE_REPORTES'], $user)) {
            $this->createSelMenu($menu);
        }

        $menu->addChild('Mi hoja de vida', ['route' => 'hv_datos_basicos'])
            ->setExtra('icon', 'far fa-address-card');

        if($this->security->isGranted(['ROLE_CREAR_VACANTE'], $user)) {
            $menu->addChild('Vacantes')
                ->setUri('#')
                ->setExtra('icon', 'fas fa-business-time');
            $menu['Vacantes']
                ->addChild('Vacantes', ['route' => 'admin_vacante_listado'])
                ->setExtra('icon', 'fas fa-clipboard-list');
            $menu['Vacantes']
                ->addChild('Hojas de vida', ['route' => 'admin_hv_listado'])
                ->setExtra('icon', 'far fa-address-card');

        }

        if($this->security->isGranted(['ROLE_VER_AUTOLIQUIDACIONES'], $user)) {
            $this->createAutoliquidacionesMenu($menu);
        }

        if($this->security->isGranted(['ROLE_ADMIN_USUARIOS'], $user)) {
            $menu->addChild('Administración')
                ->setUri('#')
                ->setExtra('icon', 'fas fa-cog');
            $menu['Administración']
                ->addChild('Usuarios', ['route' => 'admin_usuarios'])
                ->setExtra('icon', 'fas fa-users');
        }

        return $menu;
    }

    public function createSelMenu(ItemInterface $menu)
    {
        $menu->addChild('Comprobantes de pago', ['route' => 'app_comprobantes'])
            ->setExtra('icon', 'fas fa-dollar-sign');
        $menu->addChild('Certificado laboral', ['route' => 'app_certificado_laboral'])
            ->setExtra('icon', 'fas fa-file-invoice');
        $menu->addChild('Certificado ingresos', ['route' => 'app_certificados_ingresos'])
            ->setExtra('icon', 'fas fa-file-alt');
        $menu->addChild('Liquidación de contrato', ['route' => 'app_liquidaciones_de_contrato'])
            ->setExtra('icon', 'fas fa-strikethrough');
    }

    public function createHvMenu(array $options)
    {
        $menu = $this->factory->createItem('hv');
        $menu->addChild('Datos básicos', ['route' => 'hv_datos_basicos'])
            ->setExtra('icon', 'fas fa-user-circle');

        if($this->security->isGranted(['HV_MANAGE_PERSISTED'], $this->hvResolver)) {
            $menu->addChild('Formación', ['route' => 'hv_estudio'])
                ->setExtra('icon', 'fas fa-columns');
            $menu->addChild('Experiencia', ['route' => 'hv_experiencia'])
                ->setExtra('icon', 'fas fa-copy');
            $menu->addChild('Referencias', ['route' => 'hv_referencias'])
                ->setExtra('icon', 'fas fa-tasks');
            $menu->addChild('Redes sociales', ['route' => 'hv_redes_sociales'])
                ->setExtra('icon', 'fab fa-whatsapp');
            $menu->addChild('Familiares', ['route' => 'hv_familiares'])
                ->setExtra('icon', 'fas fa-child');
            $menu->addChild('Vivienda', ['route' => 'hv_vivienda'])
                ->setExtra('icon', 'fas fa-home');
            $menu->addChild('Idiomas', ['route' => 'hv_idiomas'])
                ->setExtra('icon', 'fas fa-language');
            $menu->addChild('Adjunto', ['route' => 'hv_adjunto'])
                ->setExtra('icon', 'fas fa-upload');
        }

        return $menu;
    }

    public function createAutoliquidacionesMenu(ItemInterface $menu)
    {
        $menu->addChild('Autoliquidaciones')
            ->setUri('#')
            ->setExtra('icon', 'fas fa-calendar-plus');
        $menu['Autoliquidaciones']
            ->addChild('Autoliquidaciones', ['route' => 'admin_autoliquidaciones'])
            ->setExtra('icon', 'fas fa-clock');
    }
}