<?php

namespace DigitalRespawn\BreadcrumbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package DigitalRespawn\BreadcrumbBundle\DependencyInjection
 * @author  Vincent MARIUS <vincent.marius@digitalrespawn.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('digitalrespawn_breadcrumb');

        $rootNode
            ->children()
                ->scalarNode('trans_delimiter')
                    ->defaultValue('%')
                ->end()
                ->scalarNode('trans_domain')
                    ->defaultValue('messages')
                ->end()
                ->booleanNode('enable_errors')
                    ->defaultFalse()
                ->end()
                ->scalarNode('template')
                    ->defaultValue('DigitalRespawnBreadcrumbBundle:Breadcrumb:breadcrumb.html.twig')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
