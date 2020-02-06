<?php


namespace App\Menu;


use App\Service\Hv\HvResolver;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class MenuBuilder
{
    protected $factory;
    /**
     * @var Security
     */
    protected $security;
    /**
     * @var HvResolver
     */
    protected $hvResolver;

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

        // $menu->addChild('Dashboard', ['route' => 'sel_panel'])->setExtra('icon', 'fas fa-home');

        $user = $this->security->getUser();


        $this->createSeMenu($menu, $user);
        $this->createAspirantesMenu($menu, $user);
        $this->createEvaluacionMenu($menu, $user);

        $this->createPortalClientesMenu($menu, $user);

        $this->createAdminMenu($menu, $user);
        
        if($this->security->isGranted('ROLE_SUPERADMIN', $this->security->getUser())) {
            $menu->addChild('Solicitudes', ['route' => 'admin_scraper_solicitud_list'])
                ->setExtra('icon', 'fas fa-microchip');
        }

        return $menu;
    }

    protected function createSeMenu(ItemInterface $menu, UserInterface $user)
    {
        if ($this->security->isGranted(['ROLE_VER_SE_REPORTES'], $user)) {
            $menu->addChild('Comprobantes', ['route' => 'se_comprobantes'])
                ->setExtra('icon', 'fas fa-dollar-sign');

            $menu->addChild('Certificados')
                ->setUri('#')
                ->setExtra('icon', 'fas fa-file-invoice');
            $menu['Certificados']
                ->addChild('Laboral', ['route' => 'se_certificado_laboral'])
                ->setExtra('icon', 'fas fa-file-invoice');
            $menu['Certificados']
                ->addChild('Ingresos y retenciones', ['route' => 'se_certificado_ingresos'])
                ->setExtra('icon', 'fas fa-file-alt');
            $menu['Certificados']
                ->addChild('Aportes seguridad social', ['route' => 'app_certificados_aportes'])
                ->setExtra('icon', 'fas fa-lock');

            $menu->addChild('Liquidación de contrato', ['route' => 'se_liquidacion_contrato'])
                ->setExtra('icon', 'fas fa-strikethrough');
        }
    }

    protected function createAspirantesMenu(ItemInterface $menu, UserInterface $user)
    {
        if($this->security->isGranted(['ASPIRANTES_MODULE'])) {
            if(!$this->security->isGranted(['ROLE_CREAR_VACANTE', 'ROLE_ADMIN_USUARIOS', 'ROLE_REPRESENTANTE_CLIENTE'], $user)) {
                $menu->addChild('Mi hoja de vida', ['route' => 'hv_datos_basicos'])
                    ->setExtra('icon', 'far fa-address-card');
            }

            if ($this->security->isGranted(['ROLE_CREAR_VACANTE'], $user)) {
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
        }
    }

    protected function createEvaluacionMenu(ItemInterface $menu, $user)
    {
        if(!$this->security->isGranted(['ROLE_ADMIN_EVALUACIONES', 'ROLE_OPERADOR', 'ROLE_REPRESENTANTE_CLIENTE', 'ROLE_ARCHIVO_ADMIN'], $user)) {
            /*$menu->addChild('Evaluacion')
                ->setUri('#')
                ->setExtra('icon', 'fas fa-clipboard-list');
            $menu['Evaluacion']*/
            $menu->addChild('Inducción', [
                    'route' => 'evaluacion_menu_redirect',
                    'routeParameters' => ['evaluacionSlug' => 'induccion']
                ])
                ->setExtra('icon', 'fas fa-clipboard-list');
        }
    }

    protected function createAdminMenu(ItemInterface $menu, $user)
    {
        $roles = ['ROLE_ADMIN_USUARIOS', 'ROLE_ADMIN_AUTOLIQUIDACIONES', 'ROLE_ADMIN_EVALUACIONES', 'ROLE_VER_AUTOLIQUIDACIONES'];
        $roles = array_combine($roles, $roles);
        if($this->security->isGranted($roles, $user)) {
            if($this->security->isGranted(['ROLE_EMPLEADO'], $user)) {
                $menu->addChild('Administración')
                    ->setUri('#')
                    ->setExtra('icon', 'fas fa-cog');
                $menu = $menu['Administración'];
            }

            if($this->security->isGranted([$roles['ROLE_ADMIN_USUARIOS']], $user)) {
                $menu
                    ->addChild('Usuarios', ['route' => 'admin_usuarios'])
                    ->setExtra('icon', 'fas fa-users');
            }


            if($this->security->isGranted([$roles['ROLE_VER_AUTOLIQUIDACIONES']], $user)) {
                $menu
                    ->addChild('Pagos Seg. Social', ['route' => 'admin_autoliquidacion_list'])
                    ->setExtra('icon', 'fas fa-clock');
            }

            if($this->security->isGranted([$roles['ROLE_ADMIN_EVALUACIONES'], 'ROLE_OPERADOR'], $user)) {
                $menu
                    ->addChild('Evaluaciones', ['route' => 'admin_evaluacion_resultados'])
                    ->setExtra('icon', 'fas fa-clipboard-list');
            }
        }

    }

    protected function createPortalClientesMenu(ItemInterface $menu, $user)
    {
        $childs = [];
        if($this->security->isGranted(['ROLE_ADMIN_AUTOLIQUIDACIONES', 'ROLE_ARCHIVO_CARGAR'], $user)) {
            $childs['Convenios'] = [
                'options' => ['route' => 'admin_convenio_list'],
                'extra' => ['name' => 'icon', 'value' => 'fas fa-building']
            ];
            if($this->security->isGranted('ROLE_ARCHIVO_CARGAR', $user)) {
                $childs['Archivo'] = [
                    'options' => ['route' => 'sel_admin_archivo'],
                    'extra' => ['name' => 'icon', 'value' => 'fas fa-file']
                ];
            }
        }
        if($this->security->isGranted(['ROLE_VER_AUTOLIQUIDACIONES'], $user)) {
            $childs = array_merge($childs, [
                'Trabajadores activos' => [
                    'options' => ['route' => 'clientes_trabajadores_activos'],
                    'extra' => ['name' => 'icon', 'value' => 'fas fa-users']
                ],
                'Listado nomina' => [
                    'options' => ['route' => 'clientes_listado_nomina'],
                    'extra' => ['name' => 'icon', 'value' => 'fas fa-clipboard-list']
                ],
                'Liquidación nomina' => [
                    'options' => ['route' => 'clientes_liquidaciones_nomina'],
                    'extra' => ['name' => 'icon', 'value' => 'fas fa-clipboard-list']
                ]
            ]);

        }
        if(!$this->security->isGranted(['ROLE_ADMIN', 'ROLE_ARCHIVO_CARGAR'], $user)){
            foreach ($childs as $childName => $childData) {
                $menu
                    ->addChild($childName, $childData['options'])
                    ->setExtra($childData['extra']['name'], $childData['extra']['value']);
            }
        } else {
            $menu->addChild('Portal clientes')
                ->setUri('#')
                ->setExtra('icon', 'fas fa-cog');
            foreach ($childs as $childName => $childData) {
                $menu['Portal clientes']
                    ->addChild($childName, $childData['options'])
                    ->setExtra($childData['extra']['name'], $childData['extra']['value']);
            }
        }
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




    public function createConvenioMenu(array $options)
    {
        $menu = $this->factory->createItem('convenio');
        $menu->addChild('Representantes', [
            'route' => 'admin_convenio_representantes',
            'routeParameters' => [
                'codigo' => $options['codigo']
            ]])
            //->setExtra('icon', 'fas fa-user-circle')
        ;
        $menu->addChild('Empleados', [
            'route' => 'admin_convenio_empleados',
            'routeParameters' => ['codigo' => $options['codigo']]
        ])
            //->setExtra('icon', 'fas fa-columns')
        ;

        return $menu;
    }
}