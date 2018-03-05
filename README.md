EasternColorSitemapBundle
=========================
This Symfony bundle provide another way to manage your sitemap.

Installation
------------
1. `composer require eastern-color/sitemap-bundle`
2. Enable bundle in symfony's __/app/AppKernel.php__
    - `new EasternColor\SitemapBundle\EasternColorSitemapBundle()`,

Prerequisites
-------------
- You MUST set "framework.templating" in Symfony's __config.yml__

TODO
----
- Remove services dependency on container
- Extends this README

Command
-------
1. `ec:sitemap:generate`
    - option: `--gzip`

Basic Usage (route option)
--------------------------
1. Simply add option `sitemap` to any route.
    - xml: `<option key="sitemap">true</option>`

* You may provide a list of locales using Symfony parameters, naming it __locales__
    - e.g.: `locales: ['en', 'zh']`

Advanced Usage (custom service provider)
-----
1. Create symfony service provider

```php
namespace AppBundle;

use EasternColor\SitemapBundle\Services\SitemapXmlGeneratorProvider\SitemapXmlGeneratorProviderBase;

class MySitemapXmlGeneratorProvider extends SitemapXmlGeneratorProviderBase
{
}
```

License
-------
This bundle is under the MIT license.
