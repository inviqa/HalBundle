<?php

namespace Inviqa\HalBundle\Event;

use Inviqa\HalBundle\Converters\JsonResponseConverters;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{
    private $converters;

    public function __construct(JsonResponseConverters $converters)
    {
        $this->converters = $converters;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if ($converter = $this->converters->findSupportingConverter($event->getControllerResult())) {
            $event->setResponse($converter->convert($event->getControllerResult()));
        }
    }
}
