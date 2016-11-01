<?php

namespace Brouzie\WidgetsBundle\FailureStrategy;

class OutputMessageFailureStrategy implements FailureStrategy
{
    //TODO: messages per context (different message for html/js/css)
    private $message;

    public function __construct($message = '')
    {
        $this->message = $message;
    }

    public function handleException(\Exception $exception)
    {
        return $this->message;
    }
}
