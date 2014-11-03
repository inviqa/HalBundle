<?php

namespace spec\Inviqa\HalBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler as StockHandler;
use Symfony\Component\HttpKernel\HttpCache\Esi as Esi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class FragmentHandlerSpec extends ObjectBehavior
{
    function let(StockHandler $stockHandler, Esi $esi, RequestStack $requestStack, Request $request)
    {
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($stockHandler, $esi, $requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Inviqa\HalBundle\FragmentHandler');
    }

    function it_passes_to_stock_handler_if_not_esi(StockHandler $stockHandler, Esi $esi, Request $request)
    {
        $esi->hasSurrogateEsiCapability($request)->willReturn(false);

        $stockHandler->render('/uri', 'esi', ['test'=>42])->willReturn('response');

        $this->render('/uri', 'esi', ['test'=>42])->shouldReturn('response');
    }

    function it_returns_text_snippet_for_esi(StockHandler $stockHandler, Esi $esi, Request $request)
    {
        $esi->hasSurrogateEsiCapability($request)->willReturn(true);

        $stockHandler->render('/uri', 'esi', ['test'=>42])->shouldNotBeCalled();

        $this->render('/uri', 'esi', ['test'=>42])->shouldReturn('<esi:include src="/uri" onerror="continue" />');
    }

    function it_passes_to_stock_handler_if_did_not_request_esi(StockHandler $stockHandler, Esi $esi, Request $request)
    {
        $esi->hasSurrogateEsiCapability($request)->willReturn(true);

        $stockHandler->render('/uri', 'inline', ['test'=>42])->willReturn('response');

        $this->render('/uri', 'inline', ['test'=>42])->shouldReturn('response');
    }
}
