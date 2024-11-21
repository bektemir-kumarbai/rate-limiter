<?php

namespace RateLimiter\RateLimiter\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('rate_limiter');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->integerNode('max_attempts')
            ->info('Maximum number of attempts allowed')
            ->defaultValue(5)
            ->end()
            ->integerNode('interval')
            ->info('Time interval between attempts in seconds')
            ->defaultValue(300)
            ->end()
            ->integerNode('block_duration')
            ->info('Block duration after maximum attempts are reached, in seconds')
            ->defaultValue(10800)
            ->end()
            ->end();

        return $treeBuilder;
    }
}