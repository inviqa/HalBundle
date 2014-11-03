<?php

namespace Inviqa\HalBundle;

use Nocarrier\Hal;

/**
 * @SuppressWarnings(PHPMD)
 */
class EsiHal extends Hal
{
    /**
     * Depth cuts off embedded resources. Increase if necessary.
     */
    const ARBITRARY_DEPTH = 5;

    private $esiLinks = [];
    /**
     * Decode a application/hal+json document into a Nocarrier\Hal object.
     *
     * @param string $data
     * @param int $depth
     * @static
     * @access public
     * @return \Nocarrier\Hal
     */
    public static function fromJson($data, $depth = self::ARBITRARY_DEPTH)
    {
        if ($data[0] === '<') {
            return $data;
        }

        $hal = Hal::fromJson($data, $depth);

        $esiHal = new EsiHal($hal->getUri(), $hal->getData());
        $esiHal->links = $hal->links;
        $esiHal->resources = $hal->resources;

        return $esiHal;
    }

    /**
     * Add an embedded resource, identified by $rel and represented by $resource.
     *
     * Type hint removed to allow ESI tag as string
     *
     * @param string $rel
     * @param mixed $resource
     *
     * @return \Nocarrier\Hal
     */
    public function addResourceEsi($rel, $resource = null)
    {
        if (!$resource instanceof Hal) {
            $placeholder = '__ESI-'.count($this->esiLinks).'-ESI__';
            $this->esiLinks[$placeholder] = $resource;
            $this->resources[$rel][] = $placeholder;

            return $this;
        }

        $this->resources[$rel][] = $resource;

        return $this;
    }

    /**
     * @{inheritdoc}
     */
    public function setResource($rel, $resource)
    {
        return $this->hal($rel, $resource);
    }

    /**
     * Return the current object in a application/hal+json format (links and
     * resources).
     *
     * @param bool $pretty
     *   Enable pretty-printing.
     * @param bool $encode
     *   Run through json_encode
     * @return string
     */
    public function asJson($pretty = false, $encode = true)
    {
        $renderer = new EsiHalJsonRenderer();
        $content = $renderer->render($this, $pretty, $encode);

        $placeholders = array_map(
            function ($placeholder) {
                return '"'.$placeholder.'"';
            },
            array_keys($this->esiLinks)
        );

        return str_replace($placeholders, array_values($this->esiLinks), $content);
    }
}
