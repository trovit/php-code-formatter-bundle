<?php
namespace Trovit\PhpCodeFormatterBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Trovit\PhpCodeFormatterBundle\TrovitPhpCodeFormatterBundle;

class TrovitPhpCodeFormatterExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithTemporaryFile()
    {
        $config['temporary_path'] = __DIR__;
        $container = $this->getContainerForConfig([$config]);
        $formatterManager = $container->get('trovit.php_code_formatter.managers.formatter_manager');
        $this->assertEquals("<?php echo 'hola';\n", $formatterManager->execute('<?php echo "hola"; ?>'));
    }

    public function testLoadWithoutConfig()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('The child node "temporary_path" at path "trovit_php_code_formatter" must be configured.');
        $this->getContainerForConfig([[]]);

    }

    public function testLoadWithGoodServiceFormatter()
    {
        $config['temporary_path'] = __DIR__;
        $config['formatter_services'] = ['trovit.php_code_formatter.formatters.php_cs_formatter'];
        $container = $this->getContainerForConfig([$config]);
        $formatterManager = $container->get('trovit.php_code_formatter.managers.formatter_manager');
        $this->assertEquals("<?php echo 'hola';\n", $formatterManager->execute('<?php echo "hola"; ?>'));
    }

    public function testLoadWithBadServiceFormatter()
    {
        $config['temporary_path'] = __DIR__;
        $config['formatter_services'] = ['bad_fake_service'];
        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage('You have requested a non-existent service "bad_fake_service".');
        $this->getContainerForConfig([$config]);

    }

    private function getContainerForConfig(array $configs)
    {
        $kernel = $this
            ->getMockBuilder('Symfony\Component\HttpKernel\KernelInterface')
            ->getMock();

        $kernel
            ->expects($this->any())
            ->method('getBundles')
            ->will($this->returnValue(array()));

        $bundle = new TrovitPhpCodeFormatterBundle($kernel);
        $container = new ContainerBuilder();
        $extension = $bundle->getContainerExtension();
        $container->registerExtension($extension);
        $extension->load($configs, $container);
        $bundle->build($container);
        $container->compile();

        return $container;
    }
}