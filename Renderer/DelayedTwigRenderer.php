<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class that prevents ServiceCircularReferenceException when data collector (profiler) enabled.
 */
class DelayedTwigRenderer extends TwigRenderer
{
    private $initialized = false;

    private $container;

    private $twigServiceId;

    public function __construct(ContainerInterface $container, $twigServiceId = 'twig')
    {
        $this->container = $container;
        $this->twigServiceId = $twigServiceId;
    }

    public function render(Widget $widget)
    {
        if (!$this->initialized) {
            parent::__construct($this->container->get($this->twigServiceId));
            $this->initialized = true;
        }

        return parent::render($widget);
    }
}
