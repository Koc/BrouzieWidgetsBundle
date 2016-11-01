<?php
namespace Brouzie\WidgetsBundle\Manager;

use Brouzie\WidgetsBundle\Widget\Widget;

interface WidgetManagerInterface
{
    /**
     * Instantiates and configures widget.
     *
     * @param string $name A widget name
     * @param array $options An options for pass to the widget
     *
     * @return Widget
     */
    public function createWidget($name, array $options = array());

    public function renderWidget(Widget $widget);
}
