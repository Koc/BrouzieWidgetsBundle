<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\Widget;

class ChainRenderer implements Renderer
{
    private $renderers;

    /**
     * @param Renderer[] $renderers
     */
    public function __construct(array $renderers)
    {
        $this->renderers = $renderers;
    }

    public function render(Widget $widget)
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($widget)) {
                return $renderer->render($widget);
            }
        }

        throw new \InvalidArgumentException(sprintf('No renderer configured for widget "%s"', get_class($widget)));
    }

    public function supports(Widget $widget)
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($widget)) {
                return true;
            }
        }

        return false;
    }
}
