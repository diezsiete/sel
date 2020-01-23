<?php


namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AppExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $empresa = $container->getParameterBag()->get('empresa');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach($config['empresas'] as $empresaKey => $empresaConfig) {
            $container->setParameter('empresa.'.$empresaKey.'.config', $empresaConfig);
            $container->setParameter('empresa.'.$empresaKey.'.host', $empresaConfig['host']);
            $container->setParameter('empresa.'.$empresaKey.'.db', '%env(DATABASE_URL_'.$empresaKey.')%');
        }

        $container->setParameter('scraper', $config['scraper']);
        $container->setParameter('hv_wizard_routes', $config['hv_wizard_routes']);
        $container->setParameter('sel_routes', isset($config['sel_routes']) ? $config['sel_routes'] : []);
        $container->setParameter('se', $config['se']);
        $container->setParameter('env(DB_URL)', '%%empresa.'.$empresa.'.db%%');
    }


}