<?php

namespace Brouzie\WidgetsBundle\DependencyInjection\Compiler;

use Brouzie\WidgetsBundle\Util\ContainerUtils;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterObjectNormalizersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $objectManagers = array();
        $managersLists = array(
            'doctrine.entity_managers',
            'doctrine_mongodb.odm.document_managers',
            'doctrine_couchdb.document_managers',
            'doctrine_phpcr.odm.document_managers',
        );
        foreach ($managersLists as $managersList) {
            if (!$container->hasParameter($managersList)) {
                continue;
            }

            foreach ($container->getParameter($managersList) as $entityManagerName) {
                $objectManagers[] = new Reference($entityManagerName);
            }
        }

        if ($objectManagers) {
            $doctrineNormalizerDefinition = $container
                ->getDefinition('brouzie_widgets.cache.doctrine_object_normalizer');
            $doctrineNormalizerDefinition->replaceArgument(0, $objectManagers);
        } else {
            $container->removeDefinition('brouzie_widgets.cache.doctrine_object_normalizer');
        }

        $normalizers = ContainerUtils::findAndSortTaggedServices('brouzie_widgets.object_normalizer', $container);
        $normalizers = array_map(
            function (Reference $reference) {
                return (string) $reference;
            },
            $normalizers
        );

        if ($normalizers) {
            $definition = $container->getDefinition('brouzie_widgets.cache.object_normalizer');
            $definition->replaceArgument(1, $normalizers);
        } else {
            $container->removeDefinition('brouzie_widgets.cache.object_normalizer');
        }
    }
}
