<?php

namespace Brouzie\WidgetsBundle\EventListener;

use Brouzie\WidgetsBundle\Event\WidgetEvent;
use Brouzie\WidgetsBundle\Event\WidgetEvents;
use Brouzie\WidgetsBundle\Widget\ConditionallyRenderedWidget;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConditionalListener implements EventSubscriberInterface
{
    public function render(WidgetEvent $event)
    {
        $widget = $event->getWidget();

        if (!$widget instanceof ConditionallyRenderedWidget) {
            return;
        }

        if (!$widget->shouldBeRendered()) {
            $event->setResponse('');
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            WidgetEvents::RENDER => array('render', 150),
        );
    }
}
