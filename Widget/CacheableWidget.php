<?php

namespace Brouzie\WidgetsBundle\Widget;

use Brouzie\WidgetsBundle\Cache\CacheProfile;

interface CacheableWidget
{
    /**
     * Returns cache profile for widget or null if widget shouldn't be cached (may depends on widget options or global
     * context, i.e. you can do not cache widget for authenticated users).
     *
     * @return CacheProfile|null
     */
    public function getCacheProfile();
}
