<?php

namespace Brouzie\WidgetsBundle\Exception;

use Brouzie\WidgetsBundle\Widget\Widget;

class WidgetNotImplementsException extends \RuntimeException implements Exception
{
    public function __construct($name, $object)
    {
        parent::__construct(sprintf('Widget "%s (%s)" must implement "%s"', $name, get_class($object), Widget::class));
    }
}
