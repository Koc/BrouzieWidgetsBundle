<?php

namespace Brouzie\WidgetsBundle\Manager;

use Brouzie\WidgetsBundle\Event\WidgetEvent;
use Brouzie\WidgetsBundle\Event\WidgetEvents;
use Brouzie\WidgetsBundle\FailureStrategy\FailureStrategy;
use Brouzie\WidgetsBundle\FailureStrategy\ThrowableException;
use Brouzie\WidgetsBundle\Loader\Loader;
use Brouzie\WidgetsBundle\Renderer\Renderer;
use Brouzie\WidgetsBundle\Widget\Widget;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WidgetManager implements WidgetManagerInterface
{
    private $loader;

    private $renderer;

    private $failureStrategy;

    private $dispatcher;

    private $logger;

    public function __construct(Loader $loader, Renderer $renderer, FailureStrategy $failureStrategy, EventDispatcherInterface $dispatcher, LoggerInterface $logger = null)
    {
        $this->loader = $loader;
        $this->renderer = $renderer;
        $this->failureStrategy = $failureStrategy;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    public function createWidget($name, array $options = array())
    {
        $widget = $this->loader->load($name);

        $resolver = new OptionsResolver();

        $widget->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $widget->setOptions($resolvedOptions);

        return $widget;
    }

    public function renderWidget(Widget $widget)
    {
        $widgetName = get_class($widget);
        if (null !== $this->logger) {
            $this->logger->debug('Start rendering widget.', array('widget' => $widgetName));
        }

        $event = new WidgetEvent($widget);

        //TODO: failure strategies per widget
        try {
            $this->dispatcher->dispatch(WidgetEvents::RENDER, $event);

            if ($event->hasResponse()) {
                if (null !== $this->logger) {
                    $this->logger->info('There is response for widget from listener. Renderer was not called.', array('widget' => $widgetName));
                }
            } else {
                $response = $this->renderer->render($widget);
                $event->setResponse($response);
            }

            $this->dispatcher->dispatch(WidgetEvents::RESPONSE, $event);

            return $event->getResponse();
        } catch (\Exception $e) {
            if (null !== $this->logger) {
                $this->logger->critical('An exception occurred while rendering widget.', array('widget' => $widgetName, 'exception' => $e));
            }

            return $this->failureStrategy->handleException($e);
        } catch (\Throwable $e) {
            if (null !== $this->logger) {
                $this->logger->critical('An exception occurred while rendering widget.', array('widget' => $widgetName, 'exception' => $e));
            }

            $e = new ThrowableException($e);

            return $this->failureStrategy->handleException($e);
        }
    }
}
