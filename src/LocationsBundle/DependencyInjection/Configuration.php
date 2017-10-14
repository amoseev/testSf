<?php


namespace LocationsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('locations');

        $rootNode
            ->children()
                ->arrayNode('client')
                    ->children()
                        ->arrayNode('curl')
                            ->children()
                                ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                                ->integerNode('timeout_ms')->defaultValue(1000)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}