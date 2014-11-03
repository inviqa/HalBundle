<?php

namespace Inviqa\HalBundle;

use Inviqa\HalBundle\DependencyInjection\CompilerPass\ConvertersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InviqaHalBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConvertersPass());
    }
}
