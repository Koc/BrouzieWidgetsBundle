<?php

namespace Brouzie\WidgetsBundle\Cache;

use Brouzie\WidgetsBundle\Widget\Widget;

class CacheKeyGenerator
{
    private $widget;

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    public function __invoke()
    {
        return sha1(
            serialize(
                array(
                    'widget' => $this->widget->getName(),
                    'options' => $this->widget->getOptions(),
                )
            )
        );
    }
}
