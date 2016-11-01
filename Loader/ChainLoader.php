<?php

namespace Brouzie\WidgetsBundle\Loader;

class ChainLoader implements Loader
{
    private $loaders;

    /**
     * @param Loader[] $loaders
     */
    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }

    public function load($name)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($name)) {
                return $loader->load($name);
            }
        }

        throw new \InvalidArgumentException(sprintf('No loader configured for widget "%s"', $name));
    }

    public function supports($name)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($name)) {
                return true;
            }
        }

        return false;
    }
}
