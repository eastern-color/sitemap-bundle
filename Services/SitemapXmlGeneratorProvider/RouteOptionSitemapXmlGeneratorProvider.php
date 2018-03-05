<?php

namespace EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * RouteOptionSitemapXmlGeneratorProvider
 * Generated: 2017-10-20T04:32:14+00:00.
 */
class RouteOptionSitemapXmlGeneratorProvider extends SitemapXmlGeneratorProviderBase
{
    public function generate(SymfonyStyle $io)
    {
        // Get parameters
        $domain = $this->container->getParameter('domain');
        $locales = $this->container->getParameter('locales');

        // Prepare route parameters
        $routeParamNoLocale = [];
        $routeParamLocales = [];
        foreach ($locales as $locale) {
            $routeParamLocales[$locale] = ['_locale' => $locale];
        }

        // Prepare routes data structure
        $routesNoLocale = [];
        $routesLocales = [];
        foreach ($locales as $locale) {
            $routesLocales[$locale] = [];
        }

        $routesCollection = $this->router->getRouteCollection();
        /* @var $route Route */
        foreach ($routesCollection as $id => $route) {
            if ($route->getOption('sitemap')) {
                if (strstr($route->getPath(), '{_locale}')) {
                    foreach ($locales as $locale) {
                        $routesLocales[$locale][] = $this->newRouterItem($id, $routeParamLocales[$locale]);
                    }
                } else {
                    $routes[] = $this->newRouterItem($id, $routeParamNoLocale);
                }
            }
        }

        $sitemapIndexes = [];
        $sitemapIndexes[] = $this->newItem($this->sitemapUrlPrefix.$this->generateUrlSet('_all', $routes));
        foreach ($locales as $locale) {
            $sitemapIndexes[] = $this->newItem($this->sitemapUrlPrefix.$this->generateUrlSet($locale, $routesLocales[$locale]));
        }

        $this->generateSitemap('', $sitemapIndexes);
    }
}
