<?php

namespace Brouzie\WidgetsBundle\Cache\ObjectNormalizer;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LazyObjectNormalizer
{
    private $container;

    private $normalizerServicesIds;

    private $normalizers;

    public function __construct(ContainerInterface $container, array $normalizerServicesIds)
    {
        $this->container = $container;
        $this->normalizerServicesIds = $normalizerServicesIds;
    }

    public function __invoke($object)
    {
        if (null === $this->normalizers) {
            $this->normalizers = array();

            foreach ($this->normalizerServicesIds as $normalizerServicesId) {
                $this->normalizers[] = $this->container->get($normalizerServicesId);
            }
        }

        foreach ($this->normalizers as $normalizer) {
            if (null !== $result = $normalizer($object)) {
                return $result;
            }
        }
    }
}
