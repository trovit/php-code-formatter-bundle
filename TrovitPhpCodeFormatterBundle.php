<?php

namespace Trovit\PhpCodeFormatterBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trovit\PhpCodeFormatterBundle\DependencyInjection\DynamicFormattersCompilerPass;

class TrovitPhpCodeFormatterBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DynamicFormattersCompilerPass());
    }
}
