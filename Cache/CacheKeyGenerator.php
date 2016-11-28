<?php

namespace Brouzie\WidgetsBundle\Cache;

use Brouzie\WidgetsBundle\Widget\Widget;

class CacheKeyGenerator
{
    private static $objectNormalizers = array();

    private $widget;

    public static function registerObjectNormalizer(callable $normalizer)
    {
        self::$objectNormalizers[] = $normalizer;
    }

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    public function __invoke()
    {
        $normalizedOptions = array();
        $options = $this->widget->getOptions();

        foreach ($options as $key => $option) {
            if (is_resource($option)) {
                throw new \RuntimeException('Resources are not supported.');
            }

            //TODO: handle nested arrays
            if (is_object($option)) {
                foreach (self::$objectNormalizers as $objectNormalizer) {
                    if (null !== $result = $objectNormalizer($option)) {
                        $normalizedOptions[$key] = $result;

                        continue 2;
                    }
                }

                throw new \RuntimeException(sprintf('There is no normalizer for object of "%s".', get_class($option)));
            }

            $normalizedOptions[$key] = $option;
        }

        ksort($normalizedOptions);

        return sha1(
            serialize(
                array(
                    'widget' => $this->widget->getName(),
                    'options' => $normalizedOptions,
                )
            )
        );
    }
}
