<?php


namespace Sel\RemoteBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\GlobFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SelRemoteExtension extends Extension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container)
    {
        // based on \Symfony\Component\HttpKernel\Kernel::getContainerLoader
        $locator = new FileLocator(dirname(__DIR__) . '/Resources/config');
        $loader = new DelegatingLoader(
            new LoaderResolver([new YamlFileLoader($container, $locator), new GlobFileLoader($container, $locator)])
        );
        $loader->load('{packages}/*.{php,xml,yaml,yml}', 'glob');
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('selr', $config);
    }

}