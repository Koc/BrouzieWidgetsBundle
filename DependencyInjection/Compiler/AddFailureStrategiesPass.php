<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddFailureStrategiesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $strategies = array();
        foreach ($container->findTaggedServiceIds('brouzie_widgets.failure_strategy') as $id => $tags) {
            foreach ($tags as $tag) {
                if (empty($tag['strategy'])) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Attribute "strategy" required for service "%s" tagged as "brouzie_widgets.failure_strategy".',
                            $id
                        )
                    );
                }
                $strategies[$tag['strategy']] = $id;
            }
        }

        $strategy = $container->getParameter('brouzie_widgets.failure_strategy');

        if (!isset($strategies[$strategy])) {
            throw new \InvalidArgumentException(sprintf('Strategy "%s" not exists.', $strategy));
        }

        $container->setAlias('brouzie_widgets.failure_strategy', new Alias($strategies[$strategy], false));
    }
}
