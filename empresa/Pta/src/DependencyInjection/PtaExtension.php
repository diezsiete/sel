<?php


namespace Sel\Empresa\Pta\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PtaExtension extends Extension
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

        $container->setParameter('empresa.config', $config['empresas'][$empresa]);
        foreach($config['empresas'] as $empresaKey => $empresaConfig) {
            $container->setParameter('empresa.'.$empresaKey.'.host', $empresaConfig['host']);
        }

        $container->setParameter('env(EMPRESA)', '%env(DATABASE_URL)%');
    }


}