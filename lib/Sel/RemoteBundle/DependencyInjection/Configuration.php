<?php /** @noinspection NullPointerExceptionInspection */


namespace Sel\RemoteBundle\DependencyInjection;


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
        $treeBuilder = new TreeBuilder('sel_remote');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->scalarNode('url')->end()
            ->scalarNode('empresa')->end()
        ->end();

        return $treeBuilder;
    }

}