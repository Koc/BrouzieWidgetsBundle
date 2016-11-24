<?php

namespace Brouzie\WidgetsBundle\Cache;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\CacheItem;

class CacheProfile
{
    private $key;

    private $keyItems;

    private $ttl;

    private $tags;

    /**
     * @param string|string[]|array|callable $key
     * @param int|null $ttl
     * @param array $tags
     */
    public function __construct($key, $ttl = null, array $tags = array())
    {
        if (!empty($key) && (is_callable($key) || is_string($key))) {
            $this->key = $key;
        } elseif (is_array($key)) {
            $this->keyItems = $key;
        } else {
            throw new \InvalidArgumentException('Key must be an array of key items or not empty string or callable.');
        }

        $this->ttl = $ttl;
        $this->tags = $tags;
    }

    /**
     * @return string|null
     */
    public function getKey()
    {
        $key = $this->key;

        return is_callable($key) ? $key() : $key;
    }

    /**
     * @return string[]|null
     */
    public function getKeyItems()
    {
        return $this->keyItems;
    }

    public function getTtl()
    {
        return $this->ttl;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function configureCacheItem(CacheItemInterface $item)
    {
        if (0 !== $this->ttl) {
            $item->expiresAfter($this->ttl);
        }

        if ($item instanceof CacheItem && $this->tags) {
            $item->tag($this->tags);
        }
    }
}
