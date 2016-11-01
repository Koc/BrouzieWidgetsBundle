<?php

namespace Brouzie\WidgetsBundle\Widget;

interface ConditionallyRenderedWidget
{
    /**
     * Returns true if widget should be rendered, false otherwise.
     *
     * @return bool
     */
    public function shouldBeRendered();
}
