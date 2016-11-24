<?php

namespace Brouzie\WidgetsBundle\EventListener;

use Brouzie\WidgetsBundle\Cache\CacheProfile;
use Brouzie\WidgetsBundle\Event\WidgetEvent;
use Brouzie\WidgetsBundle\Event\WidgetEvents;
use Brouzie\WidgetsBundle\Widget\CacheableWidget;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CachingListener implements EventSubscriberInterface
{
    const CACHE_KEY_PATTERN = 'widget_%s';

    private $cache;

    private $widgetsToCache = array();

    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    public function render(WidgetEvent $event)
    {
        $widget = $event->getWidget();
        if (!$widget instanceof CacheableWidget) {
            return;
        }

        $cacheProfile = $widget->getCacheProfile();
        if (!$cacheProfile instanceof CacheProfile) {
            return;
        }

        $item = $this->cache->getItem($this->getCacheKey($cacheProfile, $widget->getName()));

        if ($item->isHit()) {
            $event->setResponse($item->get());
            $event->stopPropagation();
        } else {
            // schedule store widget in cache
            $this->widgetsToCache[spl_object_hash($widget)] = true;
        }
    }

    public function storeCache(WidgetEvent $event)
    {
        $widget = $event->getWidget();
        if (!$widget instanceof CacheableWidget) {
            return;
        }

        $hash = spl_object_hash($widget);
        if (!isset($this->widgetsToCache[$hash])) {
            // widget not scheduled for caching (or maybe already cached)
            return;
        }

        $cacheProfile = $widget->getCacheProfile();
        if (!$cacheProfile instanceof CacheProfile) {
            return;
        }

        $item = $this->cache->getItem($this->getCacheKey($cacheProfile, $widget->getName()));

        $item->set($event->getResponse());
        $cacheProfile->configureCacheItem($item);
        $this->cache->save($item);
        unset($this->widgetsToCache[$hash]);
    }

    public static function getSubscribedEvents()
    {
        return array(
            WidgetEvents::RENDER => array('render', 200),
            WidgetEvents::RESPONSE => 'storeCache',
        );
    }

    private function getCacheKey(CacheProfile $cacheProfile, $widgetName)
    {
        if (null === $key = $cacheProfile->getKey()) {
            $keyItems = $cacheProfile->getKeyItems();
            $keyItems[] = $widgetName;
            $key = sha1(serialize($keyItems));
        }

        return sprintf(self::CACHE_KEY_PATTERN, $key);
    }
}
