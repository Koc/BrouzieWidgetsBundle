<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Component\Stopwatch\Stopwatch;

class StopwatchRenderer implements Renderer
{
    private $stopwatch;

    private $renderer;

    public function __construct(Stopwatch $stopwatch, Renderer $renderer)
    {
        $this->stopwatch = $stopwatch;
        $this->renderer = $renderer;
    }

    public function render(Widget $widget)
    {
        $e = $this->stopwatch->start(sprintf('Widget (%s)', get_class($widget)), 'brouzie_widgets');
        $result = $this->renderer->render($widget);
        $e->stop();

        return $result;
    }

    public function supports(Widget $widget)
    {
        return $this->renderer->supports($widget);
    }
}
