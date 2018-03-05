<?php

namespace EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * SitemapXmlGeneratorProviderChain.
 */
class SitemapXmlGeneratorProviderChain
{
    /** @var array|SitemapXmlGeneratorProviderInterface[] */
    private $providers = [];

    /** @var array|SitemapXmlGeneratorProviderInterface[] */
    private $rootProviders = [];

    /** @var array|bool[] */
    private $providersAdded = [];

    public function addProvider($id, $provider, $tags)
    {
        $this->providers[$id] = $provider;
        if (!$provider instanceof SitemapXmlGeneratorChildProviderInterface) {
            $this->providersAdded[$id] = true;
            $this->rootProviders[] = $id;
        } else {
            $this->providersAdded[$id] = false;
        }
    }

    public function buildTree()
    {
        /** @var $provider SitemapXmlGeneratorChildProviderInterface */
        foreach ($this->providers as $id => $provider) {
            if (!$this->providersAdded[$id]) {
                if (isset($this->providers[$provider->getParent()])) {
                    $this->providers[$provider->getParent()]->addChild($id, $provider);
                    $this->providersAdded[$id] = true;
                }
            }
        }
        foreach ($this->providersAdded as $value) {
            if (!$value) {
                $this->buildTree();
            }
        }
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function getByServiceId($id)
    {
        return (isset($this->providers[$id])) ? $this->providers[$id] : null;
    }

    public function init($gzip)
    {
        foreach ($this->providers as $provider) {
            $provider->init($gzip);
        }
    }

    public function generate(SymfonyStyle $io)
    {
        foreach ($this->rootProviders as $id) {
            $this->providers[$id]->generate($io);
        }
    }
}
