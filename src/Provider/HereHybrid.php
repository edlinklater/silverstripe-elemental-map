<?php

namespace EdgarIndustries\ElementalMap\Provider;

use SilverStripe\Control\Director;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\SiteConfig\SiteConfig;

class HereHybrid extends Here implements MapProviderInterface
{
    use Injectable;

    protected $title = 'HERE (Hybrid)';

    protected $variant = 'hybrid.day';
}
