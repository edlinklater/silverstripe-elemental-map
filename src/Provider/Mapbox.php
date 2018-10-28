<?php

namespace EdgarIndustries\ElementalMap\Provider;

use SilverStripe\Control\Director;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\SiteConfig\SiteConfig;

class Mapbox implements MapProviderInterface
{
    use Injectable;

    private static $title = 'Mapbox';

    private $variant = '';

    private function getBaseUrl()
    {
        return 'https://api.mapbox.com/v4/';
    }

    public function getLeafletParams()
    {
        $siteconfig = SiteConfig::current_site_config();

        return (object)[
            'attribution' => 'Imagery from <a href="http://mapbox.com/about/maps/">MapBox</a> &mdash; Map data {attribution.OpenStreetMap}',
            'id' => $siteconfig->ElementalMapMapboxId,
            'accessToken' => $siteconfig->ElementalMapMapboxToken,
            'maxZoom' => 20,
        ];
    }

    public function getTileUrl()
    {
        $url = $this->getBaseUrl() . implode('/', [
                '{id}',
                '{z}',
                '{x}',
                '{y}{r}.png',
            ]);

        return $url . '?access_token={accessToken}';
    }

    public function getTitle()
    {
        return self::$title;
    }

    public function getVariants()
    {
        return [];
    }

    public function requiresAuth()
    {
        return ['ElementalMapMapboxId', 'ElementalMapMapboxToken'];
    }

    public function requireCss()
    {
        return [];
    }

    public function requireJs()
    {
        return [];
    }
}
