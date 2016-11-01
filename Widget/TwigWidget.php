<?php

namespace Brouzie\WidgetsBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class TwigWidget implements Widget
{
    use OptionsAwareTrait;

    use NameAwareTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('_template', $this->getDefaultTemplate());
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->options['_template'];
    }

    /**
     * @return array()
     */
    public function getContext()
    {
        return array();
    }

    protected function getDefaultTemplate()
    {
        list($bundle, $widgetName) = explode(':', $this->getName());

        $path = str_replace(array('_', '\\'), '/', $widgetName);
        $template = sprintf('@%s/widgets/%sWidget.%s.twig', substr($bundle, 0, -6), $path, $this->getFormat());

        return $template;
    }

    protected function getFormat()
    {
        return 'html';
    }
}
