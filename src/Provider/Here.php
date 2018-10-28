<?php

namespace EdgarIndustries\ElementalMap\Provider;

use SilverStripe\Control\Director;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\SiteConfig\SiteConfig;

class Here implements MapProviderInterface
{
    use Injectable;

    protected $title = 'HERE';

    protected $variant = 'normal.day';

    public function getTitle()
    {
        return $this->title;
    }

    private function getBaseUrl()
    {
        $subdomain = mt_rand(1, 4);

        $type = $this->variant == 'hybrid.day' ? 'aerial' : 'base';

        return Director::isLive()
            ? 'https://' . $subdomain . '.' . $type . '.maps.api.here.com/maptile/2.1/'
            : 'https://' . $subdomain . '.' . $type . '.maps.cit.api.here.com/maptile/2.1/';
    }

    public function getLeafletParams()
    {
        $siteconfig = SiteConfig::current_site_config();

        return (object) [
            'attribution' => '<a href="https://developer.here.com/">HERE</a>',
            'app_id' => $siteconfig->ElementalMapHereId,
            'app_code' => $siteconfig->ElementalMapHereToken,
            'maxZoom' => 20,
        ];
    }

    public function getTileUrl()
    {
        $url = $this->getBaseUrl() . implode('/', [
            'maptile',
            'newest',
            $this->variant,
            '{z}',
            '{x}',
            '{y}',
            '256',
            $this->variant == 'hybrid.day' ? 'png' : 'png8',
        ]);

        return $url . '?app_id={app_id}&app_code={app_code}&lg=eng';
    }

    public function requiresAuth()
    {
        return ['ElementalMapHereId', 'ElementalMapHereToken'];
    }

    public function requireCss()
    {
        return false;
    }

    public function requireJs()
    {
        return false;
    }
}
