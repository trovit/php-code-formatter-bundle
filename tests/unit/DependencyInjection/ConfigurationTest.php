<?php
namespace Trovit\PhpCodeFormatterBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Trovit\PhpCodeFormatterBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testTemporaryPathWithoutValueAndIsRequired()
    {
        $this->assertConfigurationIsInvalid(array(array()), 'temporary_path');
    }

    public function testTemporaryPathIsNotAValidFilePath()
    {
        $this->assertConfigurationIsInvalid(
            array(
                array('temporary_path' => 'not a valid file path :(')
            ),
            'Temporary path is not a valid directory.'
        );
    }

    public function testTemporaryPathIsAValidFilePath()
    {
        $this->assertConfigurationIsValid(
            array(
                array('temporary_path' => __DIR__)
            )
        );
    }

    public function testDefaultFormatter()
    {
        $this->assertProcessedConfigurationEquals(
            array(),
            array(
                'formatter_services' => array(
                    'trovit.php_code_formatter.formatters.php_cs_formatter'
                )
            ),
            'formatter_services'
        );
    }

    public function testOnlyOneFormatterAsString()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                array('formatter_services' => 'trovit.php_code_formatter.formatters.php_cs_formatter')
            ),
            array(
                'formatter_services' => array(
                    'trovit.php_code_formatter.formatters.php_cs_formatter'
                )
            ),
            'formatter_services'
        );
    }
}
