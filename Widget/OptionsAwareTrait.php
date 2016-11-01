<?php

namespace Brouzie\WidgetsBundle\Widget;

trait OptionsAwareTrait
{
    protected $options;

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
