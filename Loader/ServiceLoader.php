<?php

namespace Brouzie\WidgetsBundle\Loader;

use Brouzie\WidgetsBundle\Exception\WidgetNotImplementsException;
use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Loader that loads widgets which configured as services and tagged with "brouzie_widgets.widget".
 */
class ServiceLoader implements Loader
{
    private $container;

    private $widgetsMap;

    /**
     * @param ContainerInterface $container
     * @param array $widgetsMap An array of widget name => service name key pairs
     */
    public function __construct(ContainerInterface $container, array $widgetsMap)
    {
        $this->container = $container;
        $this->widgetsMap = $widgetsMap;
    }

    public function load($name)
    {
        $widget = $this->container->get($this->widgetsMap[$name]);

        if (!$widget instanceof Widget) {
            throw new WidgetNotImplementsException($name, $widget);
        }

        $widget->setName($name);

        return $widget;
    }

    public function supports($name)
    {
        return isset($this->widgetsMap[$name]);
    }
}
