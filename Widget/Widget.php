<?php

namespace Brouzie\WidgetsBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface Widget
{
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
