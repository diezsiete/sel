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
                        ->arrayNode('ssrs_db')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->booleanNode('convenios')->defaultValue(true)->end()
                                ->end()
                            ->end()
                        ->end()
                        ->append($this->addOficinasNode())
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

    public function addOficinasNode()
    {
        $treeBuilder = new TreeBuilder('oficinas');

        $node = $treeBuilder->getRootNode()
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('ciudad')->end()
                    ->scalarNode('direccion')->end()
                    ->scalarNode('telefono')->end()
                    ->scalarNode('email')->end()
                    ->floatNode('latitude')->end()
                    ->floatNode('longitude')->end()
                ->end()
            ->end()
        ;
        return $node;
    }
}