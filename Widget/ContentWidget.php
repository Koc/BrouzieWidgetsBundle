<?php

namespace Brouzie\WidgetsBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ContentWidget implements Widget
{
    use OptionsAwareTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return string
     */
    abstract public function getContent();
}
