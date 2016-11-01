<?php

namespace Brouzie\WidgetsBundle\FailureStrategy;

interface FailureStrategy
{
    /**
     * @param \Exception $exception
     *
     * @return string|null
     */
    public function handleException(\Exception $exception);
}
