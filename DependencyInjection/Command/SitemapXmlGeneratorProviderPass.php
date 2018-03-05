<?php

namespace EasternColor\SitemapBundle\DependencyInjection\Command;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * SitemapXmlGeneratorProviderPass
 * Generated: 2017-10-20T04:31:59+00:00.
 */
class SitemapXmlGeneratorProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('eastern_color_sitemap_bundle.command.sitemap_xml_generator_provider_chain')) {
            return;
        }
        $definition = $container->findDefinition('eastern_color_sitemap_bundle.command.sitemap_xml_generator_provider_chain');
        $taggedServices = $container->findTaggedServiceIds('eastern_color_sitemap_bundle.command.sitemap_xml_generator_provider_pool');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addProvider', [$id, new Reference($id), $tags]);
        }

        $definition->addMethodCall('buildTree');
    }
}
