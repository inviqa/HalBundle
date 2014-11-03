<?php

namespace Inviqa\HalBundle\Converters;

use Inviqa\HalBundle\Converter;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseConverters
{
    private $converters;

    public function __construct(array $converters)
    {
        foreach ($converters as $converter) {
            if (! $converter instanceof Converter) {
                throw new \InvalidArgumentException('Only JsonResponse Converters can be used in this collection');
            }
        }
        $this->converters = $converters;
    }

    public function findSupportingConverter($toConvert)
    {
        foreach ($this->converters as $converter) {
            if ($converter->supports($toConvert)) {
                return $this->wrapConverter($converter);
            }
        }
    }

    private function wrapConverter($converter)
    {
        if ($converter instanceof JsonResponse) {
            return $converter;
        }

        return new JsonResponse($converter);
    }

    /**
     * @param $toConvert
     * @return Response
     */
    public function convert($toConvert)
    {
        if ($converter = $this->findSupportingConverter($toConvert)) {
            return $converter->convert($toConvert);
        }

        throw new \RuntimeException('No converter found for '. get_class($toConvert));
    }
}
