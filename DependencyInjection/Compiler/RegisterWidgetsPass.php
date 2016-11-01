<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterWidgetsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('brouzie_widgets.loader.service')) {
            return;
        }

        $widgets = array();
        foreach ($container->findTaggedServiceIds('brouzie_widgets.widget') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['widget'])) {
                    //TODO: autogenerate widget name?
                    throw new \InvalidArgumentException(
                        sprintf('Attribute "widget" required for service "%s" tagged as "brouzie_widgets.widget".', $id)
                    );
                }
                $widgets[$tag['widget']] = $id;
            }
        }

        $definition = $container->getDefinition('brouzie_widgets.loader.service');
        $definition->replaceArgument(1, $widgets);
    }
}
