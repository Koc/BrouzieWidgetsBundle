<?php

namespace Brouzie\WidgetsBundle\Cache\ObjectNormalizer;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;

class DoctrineObjectNormalizer
{
    /**
     * @var ObjectManager[]
     */
    private $objectManagers;

    public function __construct(array $objectManagers)
    {
        $this->objectManagers = $objectManagers;
    }

    public function __invoke($object)
    {
        $className = ClassUtils::getClass($object);

        foreach ($this->objectManagers as $objectManager) {
            if (!$objectManager->getMetadataFactory()->isTransient($className)) {
                return array($className => $objectManager->getClassMetadata($className)->getIdentifierValues($object));
            }
        }
    }
}
