<?php
namespace Inviqa\HalBundle;

use Nocarrier\Hal;
use Nocarrier\HalJsonRenderer;

/**
 * @SuppressWarnings(PHPMD)
 */
class EsiHalJsonRenderer extends HalJsonRenderer
{
    /**
     * Return an array (compatible with the hal+json format) representing
     * associated resources.
     *
     * @param mixed $resources
     * @return array
     */
    protected function resourcesForJson($resources)
    {
        if (!is_array($resources)) {
            return $this->arrayForJson($resources);
        }

        $data = array();

        foreach ($resources as $resource) {
            if ($resource instanceof Hal) {
                $resource = $this->arrayForJson($resource);
            }
            if (!empty($resource)) {
                $data[] = $resource;
            }
        }
        return $data;
    }
}
