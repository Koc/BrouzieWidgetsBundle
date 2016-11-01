<?php

namespace Brouzie\WidgetsBundle\Event;

final class WidgetEvents
{
    /**
     * @Event("Brouzie\WidgetsBundle\Event\WidgetEvent")
     */
    const RENDER = 'widget.render';

    /**
     * @Event("Brouzie\WidgetsBundle\Event\WidgetEvent")
     */
    const RESPONSE = 'widget.response';
}
