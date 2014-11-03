<?php

namespace Inviqa\HalBundle\Converters;

use Inviqa\HalBundle\Converter;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse implements Converter
{
    private $decoratedConverter;

    public function __construct(Converter $decoratedConverter)
    {
        $this->decoratedConverter = $decoratedConverter;
    }

    /**
     * @param $toConvert
     * @return Response
     */
    public function convert($toConvert)
    {
        $response = new Response($this->decoratedConverter->convert($toConvert)->asJson(true));
        $response->headers->set('Content-Type', 'application/hal+json');

        return $response;
    }

    public function supports($toConvert)
    {
        return $this->decoratedConverter->supports($toConvert);
    }
}
