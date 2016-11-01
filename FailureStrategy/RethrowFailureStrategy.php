<?php

namespace Brouzie\WidgetsBundle\FailureStrategy;

class RethrowFailureStrategy implements FailureStrategy
{
    public function handleException(\Exception $exception)
    {
        if ($exception instanceof ThrowableException) {
            $exception = $exception->getThrowable();
        }

        throw $exception;
    }
}
