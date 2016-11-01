<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterProfilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('profiler')) {
            $container->removeDefinition('brouzie_widgets.data_collector');
            $container->removeDefinition('brouzie_widgets.data_collector_widget_manager');

            return;
        }

        // replace twig renderer with delayed twig renderer for preventing ServiceCircularReferenceException
        $container->removeDefinition('brouzie_widgets.renderer.twig');
        $container->setAlias('brouzie_widgets.renderer.twig', new Alias('brouzie_widgets.renderer.twig_delayed', false));

        $container
            ->getDefinition('brouzie_widgets.data_collector_widget_manager')
            ->setDecoratedService('brouzie_widgets.widget_manager');
    }
}
