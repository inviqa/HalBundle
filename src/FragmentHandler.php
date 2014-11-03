<?php

namespace Inviqa\HalBundle;

use Symfony\Component\HttpKernel\Fragment\FragmentHandler as StockHandler;
use Symfony\Component\HttpKernel\HttpCache\Esi as Esi;
use Symfony\Component\HttpFoundation\RequestStack;

class FragmentHandler
{
    private $stockHandler;
    private $esi;
    private $requestStack;

    public function __construct(StockHandler $stockHandler, Esi $esi, RequestStack $requestStack)
    {
        $this->stockHandler = $stockHandler;
        $this->esi = $esi;
        $this->requestStack = $requestStack;
    }

    public function render($uri, $renderer, array $options = array())
    {
        if ($this->isEsi($renderer)) {
            return '<esi:include src="'.$uri.'" onerror="continue" />';
        }
        return $this->stockHandler->render($uri, $renderer, $options);
    }

    private function isEsi($renderer)
    {
        if ('esi' !== $renderer) {
            return false;
        }

        return $this->esi->hasSurrogateEsiCapability(
            $this->requestStack->getCurrentRequest()
        );
    }
}
