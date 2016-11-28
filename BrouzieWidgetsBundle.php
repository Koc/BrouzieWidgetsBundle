<?php

namespace Brouzie\WidgetsBundle;

use Brouzie\WidgetsBundle\Cache\CacheKeyGenerator;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddFailureStrategiesPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddLoadersPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddRenderersPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\RegisterObjectNormalizersPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\RegisterProfilerPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\RegisterWidgetsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BrouzieWidgetsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterWidgetsPass());
        $container->addCompilerPass(new AddLoadersPass());
        $container->addCompilerPass(new AddRenderersPass());
        $container->addCompilerPass(new AddFailureStrategiesPass());
        $container->addCompilerPass(new RegisterProfilerPass());
        $container->addCompilerPass(new RegisterObjectNormalizersPass());
    }

    public function boot()
    {
        if ($this->container->has('brouzie_widgets.cache.object_normalizer')) {
            CacheKeyGenerator::registerObjectNormalizer($this->container->get('brouzie_widgets.cache.object_normalizer'));
        }
    }
}
