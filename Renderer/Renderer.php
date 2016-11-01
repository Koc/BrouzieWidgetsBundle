<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\Widget;

interface Renderer
{
    /**
     * Renders the widget.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function render(Widget $widget);

    /**
     * Checks if this renderer able to render widget.
     *
     * @param Widget $widget
     *
     * @return bool
     */
    public function supports(Widget $widget);
}
