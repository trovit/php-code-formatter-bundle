<?php


namespace Trovit\PhpCodeFormatterBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DynamicFormattersCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $formatters = $container->getParameter('trovit_php_code_formatter.formatter_services');
        $formatterReferences = [];

        foreach ($formatters as $formatter){
            $formatterReferences[] = new Reference($formatter);
        }

        $container->getDefinition('trovit.php_code_formatter.managers.formatter_manager')
            ->setArguments([$formatterReferences]);
    }
}