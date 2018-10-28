<?php

namespace EdgarIndustries\ElementalMap\Provider;

interface MapProviderInterface
{
    /**
     * @return string Friendly name of this Map provider
     */
    public function getTitle();

    /**
     * Get parameters to use for initialising tileLayer in Leaflet.
     *
     * @return object
     */
    public function getLeafletParams();

    /**
     * Get the tile URL to use for initialising tileLayer in Leaflet.
     *
     * Can include parameters e.g. {x} and {y} which will be replaced in JavaScript.
     *
     * @return string
     */
    public function getTileUrl();

    /**
     * @return array SiteConfig parameters required for this provider to function
     *
     * @see SilverStripe\SiteConfig\SiteConfig
     */
    public function requiresAuth();

    /**
     * Array of additional CSS includes required for the provider
     *
     * @return array
     */
    public function requireCss();

    /**
     * Array of additional JavaScript includes required for the provider
     *
     * @return array
     */
    public function requireJs();
}
