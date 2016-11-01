<?php

namespace Brouzie\WidgetsBundle\Util;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContainerUtils
{
    /**
     * Finds all services with the given tag name and order them by their priority.
     *
     * @param string $tagName
     * @param ContainerBuilder $container
     *
     * @return Reference[]
     */
    public static function findAndSortTaggedServices($tagName, ContainerBuilder $container)
    {
        $services = $container->findTaggedServiceIds($tagName);
        $queue = new \SplPriorityQueue();
        foreach ($services as $serviceId => $tags) {
            foreach ($tags as $attributes) {
                $priority = isset($attributes['priority']) ? $attributes['priority'] : 0;
                $queue->insert(new Reference($serviceId), $priority);
            }
        }

        return iterator_to_array($queue, false);
    }
}
