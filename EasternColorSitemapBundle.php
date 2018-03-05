<?php

namespace EasternColor\SitemapBundle;

use EasternColor\SitemapBundle\DependencyInjection\Command\SitemapXmlGeneratorProviderPass;
use EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider\SitemapXmlGeneratorProviderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EasternColorSitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        // Compiler Pass
        $container->addCompilerPass(new SitemapXmlGeneratorProviderPass());

        // autoconfigure
        $container->registerForAutoconfiguration(SitemapXmlGeneratorProviderInterface::class)->addTag('eastern_color_sitemap_bundle.command.sitemap_xml_generator_provider_pool');
    }
}
