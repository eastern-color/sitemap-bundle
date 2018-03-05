<?php

namespace EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * SitemapXmlGeneratorProviderBase.
 */
abstract class SitemapXmlGeneratorProviderBase implements SitemapXmlGeneratorProviderInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var RouterInterface */
    protected $router;

    /** @var EngineInterface */
    protected $template;

    /** @var SitemapXmlGeneratorChildProviderInterface[] */
    protected $children = [];

    protected $gzip = false;
    protected $fileExtension = '.xml';

    protected $generateDestination;
    protected $sitemapUrlPrefix;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em, RouterInterface $router, EngineInterface $template)
    {
        $this->container = $container;
        $this->em = $em;
        $this->router = $router;
        $this->template = $template;

        $domain = $this->container->getParameter('domain');
        $this->sitemapUrlPrefix = "https://{$domain}/";
        $this->generateDestination = $this->container->getParameter('kernel.project_dir').'/web/';
    }

    public function addChild($id, SitemapXmlGeneratorChildProviderInterface $child)
    {
        $this->children[$id] = $child;
    }

    public function init($gzip)
    {
        $this->gzip = $gzip;
        $this->fileExtension .= ($gzip ? '.gz' : '');
    }

    protected function generateSitemap($filenameSlug, array $sitemapIndexes)
    {
        $filename = "sitemap{$filenameSlug}{$this->fileExtension}";
        $this->renderAndPutContent($this->generateDestination.$filename, 'EasternColorSitemapBundle:Command:Sitemap/sitemapindex.xml.twig', ['sitemap_indexes' => $sitemapIndexes]);
    }

    protected function generateUrlSet($filenameSlug, array $routesArray)
    {
        $filename = "sitemap.{$filenameSlug}{$this->fileExtension}";
        $this->renderAndPutContent($this->generateDestination.$filename, 'EasternColorSitemapBundle:Command:Sitemap/urlset.xml.twig', ['urls' => $routesArray]);

        return $filename;
    }

    protected function renderAndPutContent($dest, $twig, $context)
    {
        $result = $this->template->render($twig, $context);
        if ($this->gzip) {
            $result = gzencode($result);
        }
        file_put_contents($dest, $result);
    }

    protected function newItem($url)
    {
        return ['loc' => $url];
    }

    protected function newRouterItem($id, array $param = [])
    {
        return $this->newItem($this->generateUrl($id, $param));
    }

    protected function generateUrl($id, array $param = [])
    {
        return $this->router->generate($id, $param, UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
