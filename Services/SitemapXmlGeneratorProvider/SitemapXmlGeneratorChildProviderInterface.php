<?php

namespace EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider;

/**
 * SitemapXmlGeneratorChildProviderInterface.
 */
interface SitemapXmlGeneratorChildProviderInterface
{
    public function getParent();

    public function getFilenameSuffix();
}
