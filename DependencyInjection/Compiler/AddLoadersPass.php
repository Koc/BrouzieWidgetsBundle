<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Brouzie\WidgetsBundle\Util\ContainerUtils;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddLoadersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('brouzie_widgets.loader.chain')) {
            return;
        }

        $loaders = ContainerUtils::findAndSortTaggedServices('brouzie_widgets.loader', $container);

        if (1 === count($loaders)) {
            // Use an alias instead of wrapping it in the ChainLoader for performances when using only one
            $container->setAlias('brouzie_widgets.loader', new Alias((string) reset($loaders), false));
        } else {
            $definition = $container->getDefinition('brouzie_widgets.loader.chain');
            $definition->replaceArgument(0, $loaders);
            $container->setAlias('brouzie_widgets.loader', new Alias('brouzie_widgets.loader.chain', false));
        }
    }
}
