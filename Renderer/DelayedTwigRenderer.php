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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render(Widget $widget)
    {
        if (!$this->initialized) {
            parent::__construct($this->container->get('twig'));
            $this->initialized = true;
        }

        return parent::render($widget);
    }
}
