<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\ContentWidget;
use Brouzie\WidgetsBundle\Widget\Widget;

class ContentRenderer implements Renderer
{
    public function render(Widget $widget)
    {
        /* @var $widget ContentWidget */
        return $widget->getContent();
    }

    public function supports(Widget $widget)
    {
        return $widget instanceof ContentWidget;
    }
}
