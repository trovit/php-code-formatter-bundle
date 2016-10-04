<?php


namespace Trovit\PhpCodeFormatterBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

class DynamicFormattersCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function process(ContainerBuilder $container)
    {
        $formatters = $container->getParameter('trovit_php_code_formatter.formatter_services');
        $formatterReferences = [];

        foreach ($formatters as $formatter){
            if(!$container->hasDefinition($formatter)){
                throw new ServiceNotFoundException($formatter);
            }
            $formatterReferences[] = new Reference($formatter);
        }

        $container->getDefinition('trovit.php_code_formatter.managers.formatter_manager')
            ->setArguments([$formatterReferences]);
    }
}