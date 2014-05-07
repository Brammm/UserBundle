<?php

namespace Brammm\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('brammm_user');

        $rootNode
            ->children()
                ->scalarNode('user_repository')
                    ->isRequired()
                ->end()
            ->end();

        return $treeBuilder;
    }
} 