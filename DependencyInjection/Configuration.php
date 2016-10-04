<?php

namespace Trovit\PhpCodeFormatterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     * @throws InvalidTypeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('trovit_php_code_formatter');

        $rootNode
            ->children()
                ->scalarNode('temporary_path')
                    ->info('Path where the temporary files are going to be created if needed.')
                    ->isRequired()
                    ->validate()
                    ->always(function ($v) {
                        if(is_dir($v)){
                            return $v;
                        }
                        throw new InvalidTypeException('Temporary path is not a valid directory.'
                            ."\n".'Read the docs of the repo for more information.');
                    })->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->variableNode('formatter_services')
                    ->info("Array of strings where each string is the service reference name of a formatter")
                    ->defaultValue(array(
                        'trovit.php_code_formatter.formatters.php_cs_formatter',
                    ))
                    ->validate()
                    ->ifString()
                        ->then(function($formatter) {
                            return array($formatter);
                        })
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
