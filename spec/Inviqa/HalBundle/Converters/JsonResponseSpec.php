<?php

namespace spec\Inviqa\HalBundle\Converters;

use Inviqa\HalBundle\Converter;
use Nocarrier\Hal;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseSpec extends ObjectBehavior
{
    function let(Converter $decoratedConverter)
    {
        $this->beConstructedWith($decoratedConverter);
    }

    function it_is_a_converter()
    {
        $this->shouldHaveType('Inviqa\HalBundle\Converter');
    }

    function it_delegates_supports(Converter $decoratedConverter)
    {
        $toConvert = new \stdClass();

        $this->beConstructedWith($decoratedConverter);
        $decoratedConverter->supports($toConvert)->willReturn(true);

        $this->supports($toConvert)->shouldReturn(true);
    }

    function it_turns_converted_object_into_json_response(Converter $decoratedConverter, Hal $hal)
    {
        $toConvert = new \stdClass();
        $convertedJson = '{"response": "yep"}';

        $decoratedConverter->convert($toConvert)->willReturn($hal);
        $hal->asJson(true)->willReturn($convertedJson);

        $response = new Response($convertedJson);
        $response->headers->set('Content-Type', 'application/hal+json');

        $this->convert($toConvert)->shouldBeLike($response);
    }
}
