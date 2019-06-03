<?php


namespace App\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Security;

class MenuBuilder
{
    private $factory;
    /**
     * @var Security
     */
    private $security;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, Security $security)
    {
        $this->factory = $factory;
        $this->security = $security;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Dashboard', ['route' => 'app_main'])
            ->setExtra('icon', 'fas fa-home');

        $user = $this->security->getUser();

        if ($this->security->isGranted(['ROLE_VER_SE_REPORTES'], $user)) {
            $menu->addChild('Comprobantes de pago', ['route' => 'app_comprobantes'])
                ->setExtra('icon', 'fas fa-dollar-sign');
            $menu->addChild('Certificado laboral', ['route' => 'app_certificado_laboral'])
                ->setExtra('icon', 'fas fa-file-invoice');
            $menu->addChild('Certificado ingresos', ['route' => 'app_certificados_ingresos'])
                ->setExtra('icon', 'fas fa-file-alt');
            $menu->addChild('Liquidación de contrato', ['route' => 'app_liquidaciones_de_contrato'])
                ->setExtra('icon', 'fas fa-strikethrough');
        }

        if($this->security->isGranted(['ROLE_CREAR_VACANTE'], $user)) {
            $menu->addChild('Vacantes', ['route' => 'admin_vacante_listado'])
                ->setExtra('icon', 'fas fa-business-time');
            $menu['Vacantes']
                ->addChild('Vacantes', ['route' => 'admin_vacante_listado'])
                ->setExtra('icon', 'fas fa-clipboard-list');
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
}