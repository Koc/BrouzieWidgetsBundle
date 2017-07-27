<?php

namespace Brouzie\WidgetsBundle;

use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddFailureStrategiesPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddLoadersPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\AddRenderersPass;
use Brouzie\WidgetsBundle\DependencyInjection\Compiler\RegisterProfilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BrouzieWidgetsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddLoadersPass());
        $container->addCompilerPass(new AddRenderersPass());
        $container->addCompilerPass(new AddFailureStrategiesPass());
        $container->addCompilerPass(new RegisterProfilerPass());
    }
}
