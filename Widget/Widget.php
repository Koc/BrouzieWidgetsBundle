<?php

namespace Brouzie\WidgetsBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface Widget
{
    /**
     * Sets this widget name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Gets name of this widget.
     *
     * @return string
     */
    public function getName();

    /**
     * Configures available options for this widget.
     *
     * @param OptionsResolver $resolver The resolver for the available widget options
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Sets the resolved options to widget.
     *
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Gets the resolved options of widget.
     *
     * @return array
     */
    public function getOptions();
}
