<?php


namespace App\Menu;


use App\Service\Hv\HvResolver;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @TODO esto es temporal mientras separamos funcionalidades en modulos aparte
 * Class MenuBuilderServilabor
 * @package App\Menu
 * @deprecated
 */
class MenuBuilderServilabor extends MenuBuilder
{

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('main');

        // $menu->addChild('Dashboard', ['route' => 'sel_panel'])->setExtra('icon', 'fas fa-home');

        $user = $this->security->getUser();


        $this->createSeMenu($menu, $user);
        $this->createAspirantesMenu($menu, $user);
        $this->createEvaluacionMenu($menu, $user);

        //$this->createPortalClientesMenu($menu, $user);

        $this->createAdminMenu($menu, $user);

        //$this->createArchivoMenu($menu, $user);
        
        if($this->security->isGranted('ROLE_SUPERADMIN', $this->security->getUser())) {
            $menu->addChild('Solicitudes', ['route' => 'admin_scraper_solicitud_list'])
                ->setExtra('icon', 'fas fa-microchip');
        }




        return $menu;
    }


}