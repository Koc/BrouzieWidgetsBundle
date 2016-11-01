<?php

namespace Brouzie\WidgetsBundle\Widget;

use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ContentWidget implements Widget
{
    use OptionsAwareTrait;

    use NameAwareTrait;

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return string
     */
    abstract public function getContent();
}
