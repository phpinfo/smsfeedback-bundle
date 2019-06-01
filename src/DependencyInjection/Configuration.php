<?php
declare(strict_types=1);

namespace SmsFeedbackBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sms_feedback');

        $treeBuilder->getRootNode()
            ->children()

                // Auth login
                ->scalarNode('login')->end()

                // Auth password
                ->scalarNode('password')->end()

                // API Base URI
                ->scalarNode('uri')->end()

                // Request timeout
                ->integerNode('timeout')->end()

                // Logger config
                ->arrayNode('logger')
                    ->children()
                        // Enables logger
                        ->booleanNode('enabled')->end()

                        // Logger service name
                        ->scalarNode('service')->end()

                        // Logger message template
                        ->scalarNode('message_template')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
