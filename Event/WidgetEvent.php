<?php

namespace Brouzie\WidgetsBundle\Event;

use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\EventDispatcher\Event;

class WidgetEvent extends Event
{
    private $widget;

    private $response;

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    /**
     * @return Widget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function hasResponse()
    {
        return null !== $this->response;
    }

    /**
     * @param string $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
}
