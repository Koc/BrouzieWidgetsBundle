<?php

namespace Brouzie\WidgetsBundle\Manager;

use Brouzie\WidgetsBundle\Widget\Widget;

class DataCollectorWidgetManager implements WidgetManagerInterface
{
    private $widgetManager;

    private $widgets = array();

    public function __construct(WidgetManagerInterface $widgetManager)
    {
        $this->widgetManager = $widgetManager;
    }

    public function createWidget($name, array $options = array())
    {
        $widget = $this->widgetManager->createWidget($name, $options);
        $this->widgets[spl_object_hash($widget)] = array(
            'name' => $name,
            'class' => get_class($widget),
            'options' => $options,
            'rendered' => false,
        );

        return $widget;
    }

    public function renderWidget(Widget $widget)
    {
        $this->widgets[spl_object_hash($widget)]['rendered'] = true;

        return $this->widgetManager->renderWidget($widget);
    }

    /**
     * Passes through all unknown calls onto the widget manager object.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->widgetManager, $method), $args);
    }

    public function getCollectedWidgets()
    {
        return $this->widgets;
    }
}
