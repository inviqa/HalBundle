<?php

namespace spec\Inviqa\HalBundle\Converters;

use Inviqa\HalBundle\Converter;
use Inviqa\HalBundle\Converters\JsonResponse;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class JsonResponseConvertersSpec extends ObjectBehavior
{
    function it_is_cannot_be_constructed_with_non_converters(
        Converter $suitableConverter
    ) {
        $this->shouldThrow('InvalidArgumentException')->during(
            '__construct',
            array(array($suitableConverter, new \stdClass()))
        );
    }

    function it_returns_matching_converter(Converter $nonmatchingConverter, JsonResponse $matchingConverter)
    {
        $this->beConstructedWith(array($nonmatchingConverter, $matchingConverter));
        $toConvert = new \stdClass();
        $matchingConverter->supports($toConvert)->willReturn(true);
        $nonmatchingConverter->supports($toConvert)->willReturn(false);

        $this->findSupportingConverter($toConvert)->shouldReturn($matchingConverter);
    }

    function it_wraps_non_json_response_converter(Converter $nonmatchingConverter, Converter $matchingConverter)
    {
        $this->beConstructedWith(array($nonmatchingConverter, $matchingConverter));
        $toConvert = new \stdClass();
        $matchingConverter->supports($toConvert)->willReturn(true);
        $nonmatchingConverter->supports($toConvert)->willReturn(false);

        $this->findSupportingConverter($toConvert)->shouldHaveType('Inviqa\HalBundle\Converters\JsonResponse');
    }
}
