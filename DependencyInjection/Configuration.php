<?php

namespace Brouzie\WidgetsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    private $debug;

    public function __construct($debug)
    {
        $this->debug = $debug;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brouzie_widgets');

        $rootNode
            ->children()
                ->scalarNode('cache_provider')
                    ->defaultNull()
                    ->info('Service name of the cache pool.')
                    ->example('cache.app')
                ->end()
                //TODO: configure message
                ->scalarNode('failure_strategy')
                    ->defaultValue($this->debug ? 'rethrow' : 'output_message')
                ->end()
                ->booleanNode('logging')
                    ->defaultValue($this->debug)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
