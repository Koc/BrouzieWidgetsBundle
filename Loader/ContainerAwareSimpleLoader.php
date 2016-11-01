<?php

namespace Brouzie\WidgetsBundle\Loader;

use Brouzie\WidgetsBundle\Exception\WidgetNotImplementsException;
use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerAwareSimpleLoader implements Loader
{
    private $container;

    private $bundlesMap;

    /**
     * @param ContainerInterface $container
     * @param array $bundlesMap An array of bundle name => bundle class key pairs
     */
    public function __construct(ContainerInterface $container, array $bundlesMap)
    {
        $this->container = $container;
        $this->bundlesMap = $bundlesMap;
    }

    public function load($name)
    {
        list($bundle, $widget) = explode(':', $name);

        if (!isset($this->bundlesMap[$bundle])) {
            throw new \InvalidArgumentException(sprintf('Bundle "%s" not exists.', $bundle));
        }

        $bundleClass = $this->bundlesMap[$bundle];
        $bundleNamespace = substr($bundleClass, 0, -strrpos($bundleClass, '\\'));
        $widgetClass = sprintf('%s\\Widget\\%sWidget', $bundleNamespace, $widget);

        $widget = new $widgetClass();

        if (!$widget instanceof Widget) {
            throw new WidgetNotImplementsException($name, $widget);
        }

        $widget->setName($name);

        if ($widget instanceof ContainerAwareInterface) {
            $widget->setContainer($this->container);
        }

        return $widget;
    }

    public function supports($name)
    {
        return strpos($name, 'Bundle:') > 0;
    }
}
