<?php

namespace Brouzie\WidgetsBundle\Twig\Extension;

use Brouzie\WidgetsBundle\Manager\WidgetManagerInterface;
use Brouzie\WidgetsBundle\Widget\Widget;

class WidgetExtension extends \Twig_Extension
{
    protected $manager;

    public function __construct(WidgetManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('brouzie_widget', array($this, 'createWidget')),
            //TODO: use is_safe_callback to determinate safe context
            new \Twig_SimpleFunction('brouzie_render_widget', array($this, 'renderWidget'), array('is_safe' => array('html'))),
        );
    }

    public function createWidget($name, array $options = array())
    {
        return $this->manager->createWidget($name, $options);
    }

    /**
     * @param string|Widget $widget
     * @param array $options
     *
     * @return string
     */
    public function renderWidget($widget, array $options = array())
    {
        if ($widget instanceof Widget) {
            if ($options) {
                throw new \LogicException('It is not possible set options in initialized widgets.');
            }

            return $this->manager->renderWidget($widget);
        }

        if (!is_string($widget)) {
            throw new \InvalidArgumentException(sprintf('Widget should be string or object implements "%s"', Widget::class));
        }

        $widget = $this->manager->createWidget($widget, $options);

        return $this->manager->renderWidget($widget);
    }
}
