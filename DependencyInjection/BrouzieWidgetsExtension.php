<?php

namespace Brouzie\WidgetsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BrouzieWidgetsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('loaders.xml');
        $loader->load('renderers.xml');
        $loader->load('listeners.xml');
        $loader->load('failure_strategies.xml');
        $loader->load('manager.xml');
        $loader->load('twig.xml');
        $loader->load('debug.xml');

        if ($config['cache_provider']) {
            $container
                ->getDefinition('brouzie_widgets.event_listener.caching_listener')
                ->replaceArgument(0, new Reference($config['cache_provider']));
        } else {
            $container->removeDefinition('brouzie_widgets.event_listener.caching_listener');
        }

        $container->setParameter('brouzie_widgets.failure_strategy', $config['failure_strategy']);

        if ($config['logging']) {
            $container
                ->getDefinition('brouzie_widgets.widget_manager')
                ->replaceArgument(4, new Reference('logger', ContainerInterface::NULL_ON_INVALID_REFERENCE))
                ->addTag('monolog.logger', array('channel' => 'brouzie_widgets'))
            ;
        } else {
            $container
                ->getDefinition('brouzie_widgets.widget_manager')
                ->replaceArgument(4, null)
            ;
        }
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration($container->getParameter('kernel.debug'));
    }
}
