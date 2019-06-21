<?php


namespace App\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('app');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->arrayNode('empresas')
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('host')->end()
                        ->scalarNode('razon')->end()
                        ->scalarNode('nit')->end()
                        ->scalarNode('dir')->end()
                        ->scalarNode('web')->end()
                        ->scalarNode('mail')->end()
                        ->scalarNode('tel')->end()
                        ->scalarNode('logo')->end()
                        ->scalarNode('logo_pdf')->end()

                        ->arrayNode('certificado_laboral')
                            ->children()
                                ->scalarNode('firma')->end()
                                ->scalarNode('firmante')->end()
                                ->scalarNode('firmante_cargo')->end()
                                ->scalarNode('firmante_contacto')->end()
                            ->end()
                        ->end()
                        ->arrayNode('templates')
                            ->children()
                                ->scalarNode('login')->end()
                            ->end()
                        ->end()
                        ->scalarNode('contacto_email')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}