<?php

namespace Brouzie\WidgetsBundle\Loader;

use Brouzie\WidgetsBundle\Exception\WidgetNotImplementsException;
use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loader that loads widgets which configured as services.
 */
class ServiceLoader implements Loader
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function load($name)
    {
        $widget = $this->container->get($name);

        if (!$widget instanceof Widget) {
            throw new WidgetNotImplementsException($name, $widget);
        }

        return $widget;
    }

    public function supports($name)
    {
        return $this->container->has($name);
    }
}
