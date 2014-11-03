<?php

namespace spec\Inviqa\HalBundle\Event;

use Inviqa\HalBundle\Converters\JsonResponseConverters;
use Inviqa\HalBundle\Converters\JsonResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListenerSpec extends ObjectBehavior
{
    function it_should_convert_with_suitable_converter(
        GetResponseForControllerResultEvent $event,
        JsonResponseConverters $converters,
        JsonResponse $matchingConverter,
        Response $reponse
    ) {
        $this->beConstructedWith($converters);

        $controllerResult = new \stdClass();

        $event->setResponse($reponse)->shouldBeCalled();

        $event->getControllerResult()->willReturn($controllerResult);
        $converters->findSupportingConverter($controllerResult)->willReturn($matchingConverter);
        $matchingConverter->convert($controllerResult)->willReturn($reponse);

        $this->onKernelView($event);
    }

    function it_should_not_set_the_response_if_no_suitable_converter_found(
        GetResponseForControllerResultEvent $event,
        JsonResponseConverters $converters,
        Response $reponse
    ) {
        $this->beConstructedWith($converters);

        $controllerResult = new \stdClass();

        $event->setResponse(Argument::cetera())->shouldNotBeCalled();

        $event->getControllerResult()->willReturn($controllerResult);
        $converters->findSupportingConverter($controllerResult)->willReturn(null);

        $this->onKernelView($event);
    }
}
