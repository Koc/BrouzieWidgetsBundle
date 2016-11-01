<?php

namespace Brouzie\WidgetsBundle\Tests\DependencyInjection;

use Brouzie\WidgetsBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFailureStrategies
     */
    public function testDefaultFailluresStrategirs($debug, $strategy)
    {
        $config = $this->processConfiguration(new Configuration($debug), array());

        $this->assertSame($strategy, $config['failure_strategy']);
    }

    public function getFailureStrategies()
    {
        return array(
            array(false, 'output_message'),
            array(true, 'rethrow'),
        );
    }

    /**
     * @param ConfigurationInterface $configuration
     * @param array $configs
     *
     * @return array
     */
    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
