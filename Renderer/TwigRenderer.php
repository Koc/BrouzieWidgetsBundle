<?php

namespace Brouzie\WidgetsBundle\Renderer;

use Brouzie\WidgetsBundle\Widget\TwigWidget;
use Brouzie\WidgetsBundle\Widget\Widget;

class TwigRenderer implements Renderer
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(Widget $widget)
    {
        /* @var $widget TwigWidget */
        $context = $widget->getContext();

        if (!is_array($context)) {
            throw new \LogicException(sprintf('Method "%s::getContext" must return array.', get_class($widget)));
        }

        $context['_widget'] = $widget;
        $context['_options'] = $widget->getOptions();

        return $this->twig->render($widget->getTemplate(), $context);
    }

    public function supports(Widget $widget)
    {
        return $widget instanceof TwigWidget;
    }
}
