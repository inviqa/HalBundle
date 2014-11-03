<?php

namespace spec\Inviqa\HalBundle;

use Inviqa\HalBundle\EsiHal;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EsiHalSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('http://example.com', ['content' => 'Hello "World!"']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nocarrier\Hal');
    }

    function it_should_return_rendered_json()
    {
        $this->asJson(true)->shouldBe(
'{
    "content": "Hello \"World!\"",
    "_links": {
        "self": {
            "href": "http://example.com"
        }
    }
}'
        );
    }

    function it_does_not_escape_esi_tags_return_rendered_json()
    {
        $this->addResourceEsi('animals', EsiHal::fromJson('<esi src="http://badgers.example.com">'));
        $this->asJson(true)->shouldBe(
'{
    "content": "Hello \"World!\"",
    "_links": {
        "self": {
            "href": "http://example.com"
        }
    },
    "_embedded": {
        "animals": <esi src="http://badgers.example.com">
    }
}'
        );
    }
}
