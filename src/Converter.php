<?php

namespace Inviqa\HalBundle;

interface Converter
{
    public function convert($toConvert);

    public function supports($toConvert);
}
