<?php

namespace Inviqa\HalBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ConvertersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('inviqa.hal.converters')) {
            return;
        }

        $definition = $container->getDefinition('inviqa.hal.converters');
        $converters = $definition->getArgument(0);
        $taggedServices = $container->findTaggedServiceIds('inviqa.hal.converter');

        foreach (array_keys($taggedServices) as $id) {
            $converters[] = new Reference($id);
        }

        $definition->replaceArgument(0, $converters);
    }
}
