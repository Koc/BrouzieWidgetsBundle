<?php

namespace Brouzie\WidgetsBundle\Loader;

use Brouzie\WidgetsBundle\Widget\Widget;

interface Loader
{
    /**
     * Loads widget by given name.
     *
     * @param string $name
     *
     * @return Widget
     */
    public function load($name);

    /**
     * Checks if this loader able to load widget by given name.
     *
     * @param string $name
     *
     * @return bool
     */
    public function supports($name);
}
