<?php


namespace App\Menu;


use App\Service\Configuracion\Configuracion;
use App\Service\Hv\HvResolver;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Security;

class MenuBuilderFactory
{
    /**
     * TODO temporal mientras definimos funcionalidades por modulos
     * @param FactoryInterface $factory
     * @return MenuBuilder|MenuBuilderServilabor
     * @deprecated
     */
    public static function test($factory, Security $security, HvResolver $hvResolver, Configuracion $configuracion)
    {
        return $configuracion->getEmpresa(true) === 'servilabor'
            ? new MenuBuilderServilabor($factory, $security, $hvResolver)
            : new MenuBuilder($factory, $security, $hvResolver);
    }
}