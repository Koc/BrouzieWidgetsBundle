<?php

namespace Brouzie\WidgetsBundle\DataCollector;

use Brouzie\WidgetsBundle\Manager\DataCollectorWidgetManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;

class WidgetsDataCollector extends DataCollector implements LateDataCollectorInterface
{
    private $widgetManager;

    public function __construct(DataCollectorWidgetManager $widgetManager)
    {
        $this->widgetManager = $widgetManager;
    }

    public function lateCollect()
    {
        $widgets = $this->widgetManager->getCollectedWidgets();
        foreach ($widgets as $i => $widget) {
            $options = $widget['options'];
            // BC with Symfony < 3.2
            $options = method_exists($this, 'cloneVar') ? $this->cloneVar($options) : $this->varToString($options);
            $widgets[$i]['options'] = $options;
        }

        $this->data['widgets'] = $widgets;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
    }

    public function getWidgetsCreatedCount()
    {
        return count($this->data['widgets']);
    }

    public function getWidgetsRenderedCount()
    {
        $count = 0;
        foreach ($this->data['widgets'] as $widget) {
            if ($widget['rendered']) {
                ++$count;
            }
        }

        return $count;
    }

    public function getWidgets()
    {
        return $this->data['widgets'];
    }

    public function getName()
    {
        return 'brouzie_widgets';
    }
}
