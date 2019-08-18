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
            ->append($this->addScrapperNode())
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
                        ->scalarNode('home_route')->end()
                        ->arrayNode('certificado_laboral')
                            ->children()
                                ->scalarNode('firma')->end()
                                ->scalarNode('firmante')->end()
                                ->scalarNode('firmante_cargo')->end()
                                ->scalarNode('firmante_contacto')->end()
                            ->end()
                        ->end()
                        ->arrayNode('ssrs_db')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->booleanNode('convenios')->defaultValue(true)->end()
                                ->end()
                            ->end()
                        ->end()
                        ->append($this->addEmailsNode())
                        ->append($this->addOficinasNode())
                        ->append($this->addHvWizardRoutes())
                        ->append($this->addScrapperEmpresaNode())
                        ->append($this->addDocumentosLaborales())
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

    protected function addOficinasNode()
    {
        $treeBuilder = new TreeBuilder('oficinas');

        $node = $treeBuilder->getRootNode()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('nombre')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('ciudad')->end()
                    ->scalarNode('direccion')->end()
                    ->scalarNode('telefono')->end()
                    ->scalarNode('email')->end()
                    ->floatNode('latitude')->end()
                    ->floatNode('longitude')->end()
                    ->booleanNode('principal')->defaultValue(false)->end()
                ->end()
            ->end()
        ;
        return $node;
    }

    protected function addEmailsNode()
    {
        $treeBuilder = new TreeBuilder('emails');
        $node =
            $treeBuilder->getRootNode()->children()
                ->variableNode('contacto')
                    ->validate()
                        ->ifTrue(function ($v) {
                            return false === is_string($v) && false === is_array($v);
                        })
                        ->thenInvalid('Here you message about why it is invalid')
                    ->end()
                ->end()
            ->end();
        return $node;
    }

    protected function addHvWizardRoutes()
    {
        $treeBuilder = new TreeBuilder('hv_wizard_routes');
        $node =
            $treeBuilder->getRootNode()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('key')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('route')->end()
                        ->scalarNode('titulo')->end()
                    ->end()
                ->end()
        ;
        return $node;
    }

    protected function addScrapperNode()
    {
        $treeBuilder = new TreeBuilder('scrapper');
        $node =
            $treeBuilder->getRootNode()
                ->children()
                    ->scalarNode('url')->end()
                ->end();
        return $node;
    }

    protected function addScrapperEmpresaNode()
    {
        $treeBuilder = new TreeBuilder('scrapper');
        $node =
            $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('ael')
                        ->children()
                            ->scalarNode('empleador')->end()
                        ->end()
                    ->end()
                ->end();
        return $node;
    }

    protected function addDocumentosLaborales()
    {
        $treeBuilder = new TreeBuilder('documentos_laborales');

        $node = $treeBuilder->getRootNode()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('nombre')
            ->normalizeKeys(false)
            ->arrayPrototype()
                ->children()
                    ->scalarNode('date')->end()
                    ->scalarNode('title')->end()
                    ->scalarNode('pdf')->defaultValue(null)->end()
                ->end()
            ->end()
        ;
        return $node;
    }

}