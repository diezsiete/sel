<?php


namespace App\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('app');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->append($this->addScraperNode())
            ->append($this->addNovasoftApiNode())
            ->append($this->addSeNode())
            ->append($this->addSelRoutesNode())
            ->append($this->addHvWizardRoutes())
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
                        ->scalarNode('php_exec')->defaultValue('php')->end()
                        ->arrayNode('certificado_laboral')
                            ->children()
                                ->scalarNode('firma')->end()
                                ->scalarNode('firmante')->end()
                                ->scalarNode('firmante_cargo')->end()
                                ->scalarNode('firmante_contacto')->end()
                            ->end()
                        ->end()
                        ->append($this->addSsrsDbNode())
                        ->append($this->addEmailsNode())
                        ->append($this->addOficinasNode())
                        ->append($this->addDocumentosLaborales())
                        ->append($this->addCompaniasNode())
                        ->append($this->addScraperEmpresaNode())
                        ->append($this->addNovasoftApiEmpresaNode())
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

    protected function addSsrsDbNode()
    {
        $treeBuilder = new TreeBuilder('ssrs_db');
        $node =
            $treeBuilder->getRootNode()
                ->useAttributeAsKey('name')
                ->arrayPrototype()
                    ->children()
                        ->booleanNode('convenios')->defaultValue(true)->end()
                        ->arrayNode('reportes')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('empleado')->defaultValue('NomU1503')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

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

    protected function addScraperNode()
    {
        $treeBuilder = new TreeBuilder('scraper');
        $node =
            $treeBuilder->getRootNode()
                ->children()
                    ->scalarNode('url')->end()
                    ->arrayNode('novasoft')
                        ->children()
                            ->scalarNode('conexion')->end()
                        ->end()
                    ->end()
                    ->arrayNode('ael')
                        ->children()
                            ->scalarNode('user')->end()
                            ->scalarNode('password')->end()
                            ->scalarNode('empleador')->end()
                        ->end()
                    ->end()
                ->end();
        return $node;
    }

    protected function addNovasoftApiNode()
    {
        $treeBuilder = new TreeBuilder('novasoftapi');
        $node =
            $treeBuilder->getRootNode()
                ->children()
                    ->scalarNode('url')->end()
                ->end();
        return $node;
    }

    protected function addSeNode()
    {
        $treeBuilder = new TreeBuilder('se');
        return
            $treeBuilder->getRootNode()
                ->children()
                    ->arrayNode('report')
                        ->children()
                            ->arrayNode('Nomina')
                                ->children()
                                    ->scalarNode('refresh_interval')->defaultValue('P01D')->end()
                                ->end()
                            ->end()
                            ->arrayNode('CertificadoLaboral')
                                ->children()
                                    ->scalarNode('refresh_interval')->defaultValue('P01D')->end()
                                ->end()
                            ->end()
                            ->arrayNode('CertificadoIngresos')
                                ->children()
                                    ->scalarNode('refresh_interval')->defaultValue('P01D')->end()
                                    ->arrayNode('anos')->defaultValue([2017, 2018])
                                        ->scalarPrototype()->end()
                                    ->end()
                                ->end()
                            ->end()
                            ->arrayNode('LiquidacionContrato')
                                ->children()
                                    ->scalarNode('refresh_interval')->defaultValue('P01D')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();
    }

    protected function addScraperEmpresaNode()
    {
        $treeBuilder = new TreeBuilder('scraper');
        return $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('novasoft')
                    ->children()
                        ->scalarNode('browser')->end()
                    ->end()
                ->end()
            ->end();
    }

    protected function addNovasoftApiEmpresaNode()
    {
        $treeBuilder = new TreeBuilder('novasoftapi');
        return $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('db')
                    ->beforeNormalization()
                    ->ifString()
                        ->then(static function($v) { return [$v]; })
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    protected function addSelRoutesNode()
    {
        $treeBuilder = new TreeBuilder('sel_routes');
        return $treeBuilder->getRootNode()
            // ->canBeEnabled()
            ->children()
                ->arrayNode('ignore')
                    // ->canBeEnabled()
                    ->scalarPrototype()->end()
                ->end()
            ->end();
    }


    protected function addDocumentosLaborales()
    {
        $treeBuilder = new TreeBuilder('documentos_laborales');

        return $treeBuilder->getRootNode()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('nombre')
            ->normalizeKeys(false)
            ->arrayPrototype()
                ->children()
                    ->scalarNode('date')->end()
                    ->scalarNode('title')->end()
                    ->scalarNode('pdf')->defaultValue(null)->end()
                ->end()
            ->end();
    }

    private function addCompaniasNode()
    {
        $treeBuilder = new TreeBuilder('companias');
        $node = $treeBuilder->getRootNode()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('nombre')
            ->normalizeKeys(false)
            ->arrayPrototype()
                ->children()
                    ->scalarNode('razon')->end()
                    ->scalarNode('nit')->end()
                    ->scalarNode('dir')->end()
                    ->scalarNode('tel')->end()
                    ->scalarNode('web')->defaultValue(null)->end()
                    ->scalarNode('logo_pdf')->end()
                ->end()
            ->end()
        ;
        return $node;
    }

}