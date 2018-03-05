<?php

namespace EasternColor\SitemapBundle\Command;

use EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider\SitemapXmlGeneratorProviderChain;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see https://www.sitemaps.org/protocol.html
 */
class GenerateSitemapXmlCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ecsitemap:generate')
            ->setDescription('Generate Sitemap XML')
            ->setHelp('This command is to Generate Sitemap XML')
            ->addOption('gzip', null, InputOption::VALUE_NONE, 'Gzip sitemap')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('[360img.net] Generate Sitemap XML');
        $gzip = (bool) $input->getOption('gzip');

        /* @var $sitemapXmlGeneratorProviderChain SitemapXmlGeneratorProviderChain */
        $sitemapXmlGeneratorProviderChain = $this->getContainer()->get('eastern_color_sitemap_bundle.command.sitemap_xml_generator_provider_chain');
        $sitemapXmlGeneratorProviderChain->init($gzip);
        $sitemapXmlGeneratorProviderChain->generate($io);

        $io->text('Done');
    }
}
