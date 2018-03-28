<?php

namespace Druidvav\SimpleOauthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    /**
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dv_simple_oauth');
        $rootNode
            ->children()
                ->scalarNode('redirect_uri_route')
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
            ->end()
            ->append($this->addServicesSection())
        ;

        $this->addHttpClientConfiguration($rootNode);
        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function addServicesSection()
    {
        $tree = new TreeBuilder();
        $node = $tree->root('services');
        $node
            ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('title')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                    ->scalarNode('resource_owner')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                    ->variableNode('options')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                ->end()
            ->end()
        ->end();
        return $node;
    }

    private function addHttpClientConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('http')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client')->defaultValue('httplug.client.default')->end()
                        ->scalarNode('message_factory')->defaultValue('httplug.message_factory.default')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
