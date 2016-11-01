<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Brouzie\WidgetsBundle\Util\ContainerUtils;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddRenderersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('brouzie_widgets.renderer.chain')) {
            return;
        }

        $renderers = ContainerUtils::findAndSortTaggedServices('brouzie_widgets.renderer', $container);

        if (1 === count($renderers)) {
            // Use an alias instead of wrapping it in the ChainRenderer for performances when using only one
            $container->setAlias('brouzie_widgets.renderer', new Alias((string) reset($renderers), false));
        } else {
            $definition = $container->getDefinition('brouzie_widgets.renderer.chain');
            $definition->replaceArgument(0, $renderers);
            $container->setAlias('brouzie_widgets.renderer', new Alias('brouzie_widgets.renderer.chain', false));
        }

        if ($container->getParameter('kernel.debug')) {
            $container
                ->getDefinition('brouzie_widgets.renderer.stopwatch')
                ->setDecoratedService('brouzie_widgets.renderer');
        }
    }
}
