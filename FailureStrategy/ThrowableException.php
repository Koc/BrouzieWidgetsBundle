<?php

namespace Brouzie\WidgetsBundle\FailureStrategy;

class ThrowableException extends \Exception
{
    private $throwable;

    public function __construct(\Throwable $throwable)
    {
        $this->throwable = $throwable;

        parent::__construct($throwable->getMessage(), $throwable->getCode(), $throwable->getPrevious());
    }

    public function getThrowable()
    {
        return $this->throwable;
    }
}
