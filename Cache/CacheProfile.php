<?php

namespace Brouzie\WidgetsBundle\Cache;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\CacheItem;

class CacheProfile
{
    private $key;

    private $ttl;

    private $tags;

    /**
     * @param string|string[] $key
     * @param int|null $ttl
     * @param array $tags
     */
    public function __construct($key, $ttl = null, array $tags = array())
    {
        if (is_array($key)) {
            $key = sha1(serialize($key));
        }
        $this->key = $key;
        $this->ttl = $ttl;
        $this->tags = $tags;
    }

    public function getKey()
    {
        return $this->key;
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
