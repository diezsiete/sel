<?php


namespace App\Menu;


use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Dashboard', ['route' => 'app_main'])
            ->setExtra('icon', 'fas fa-home');

        $menu->addChild('Servicio empleados')
            ->setUri('#')
            ->setExtra('icon', 'fas fa-columns');

        $menu['Servicio empleados']
            ->addChild('Comprobantes de pago', ['route' => 'app_comprobantes'])
            ->setExtra('icon', 'fas fa-dollar-sign');
        $menu['Servicio empleados']
            ->addChild('Certificado laboral', ['route' => 'app_certificado_laboral'])
            ->setExtra('icon', 'fas fa-file-invoice');
        $menu['Servicio empleados']
            ->addChild('Certificado ingresos', ['route' => 'app_certificados_ingresos'])
            ->setExtra('icon', 'fas fa-file-alt');

        return $menu;
    }
}