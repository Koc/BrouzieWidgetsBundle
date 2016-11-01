<?php

namespace Brouzie\WidgetsBundle\Command;

use Brouzie\WidgetsBundle\Widget\Widget;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CollectWidgetsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('brouzie:widgets:collect-widgets');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $finder = Finder::create()
            ->files()
            ->in($container->getParameter('kernel.root_dir').'/../src')
            ->path('Widget')
            ->name('*Widget.php');

        foreach ($finder as $file) {
            include_once $file->getPathname();
        }

        $bundlesToNamespaces = array();
        foreach ($container->get('kernel')->getBundles() as $bundle) {
            $bundlesToNamespaces[$bundle->getName()] = $bundle->getNamespace();
        }

        $widgets = array();
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, Widget::class)) {
                foreach ($bundlesToNamespaces as $bundle => $namespace) {
                    if (0 === strpos($class, $namespace)) {
                        $widget = substr($class, 0, -strlen('Widget'));
                        $widget = sprintf('%s:%s', $bundle, substr($widget, strrpos($widget, '\\') + 1));
                        $widgets[$widget] = $class;
                        break;
                    }
                }
            }
        }

        $items = array();
        foreach ($widgets as $widget => $class) {
            $items[] = array(
                'lookup_string' => $widget,
                'type' => $class,
                'target' => $class,
            );
        }
        $json = array(
            'providers' => array(
                array(
                    'name' => 'brouzie_widgets.widgets',
                    'defaults' => array(
                        'icon' => 'com.intellij.util.PlatformIcons.CLASS_ICON',
                    ),
                    'items' => $items,
                ),
            ),
        );

//        dump($widgets);

        $file = $container->getParameter('kernel.root_dir').'/ide-toolbox/.ide-toolbox.metadata.json';
        if (!is_dir($dir = dirname($file))) {
            mkdir($dir);
        }

        file_put_contents($file, json_encode($json));
    }
}
