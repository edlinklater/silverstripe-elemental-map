<?php

namespace EdgarIndustries\ElementalMap\Block;

use DNADesign\Elemental\Models\BaseElement;
use EdgarIndustries\ElementalMap\Model\MapMarker;
use SilverStripe\Control\Director;

class MapBlock extends BaseElement
{
    private static $icon = 'font-icon-rocket';

    private static $db = [
        'Provider' => 'Enum(array("HERE normalDay","HERE hybridDay","MapBox","OpenStreetMap Mapnik","OpenTopoMap"), "OpenStreetMap Mapnik")',
        'ProviderID' => 'Varchar(255)',
        'ProviderToken' => 'Varchar(255)',
        'Height' => 'Int',
        'DefaultLatitude' => 'Decimal(9,6)',
        'DefaultLongitude' => 'Decimal(9,6)',
        'DefaultZoom' => 'Int',
    ];

    private static $many_many = [
        'Markers' => MapMarker::class,
    ];

    private static $defaults = [
        'DefaultZoom' => 14,
    ];

    private static $singular_name = 'map';

    private static $plural_name = 'maps';

    private static $table_name = 'Edgar_EB_MapBlock';

    protected static $requires_auth = [
        'HERE normalDay',
        'HERE hybridDay',
        'MapBox',
    ];

    public function getProviderDotted()
    {
        return str_replace(' ', '.', $this->Provider);
    }

    public function getProviderLive()
    {
        return Director::isLive();
    }

    public function getProviderOptions()
    {
        $options = [];

        if ($this->Provider == 'HERE normalDay' || $this->Provider == 'HERE hybridDay') {
            $options = [
                'app_id' => $this->ProviderID,
                'app_code' => $this->ProviderToken,
            ];
        } elseif ($this->Provider == 'MapBox') {
            $options = [
                'id' => $this->ProviderID,
                'accessToken' => $this->ProviderToken,
            ];
        }

        return json_encode((object) $options);
    }

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Map');
    }

    public function validate()
    {
        $result = parent::validate();

        if (in_array($this->Provider, self::$requires_auth)) {
            if (empty($this->ProviderID)) {
                $result->addFieldError('ProviderID', 'Required for ' . $this->Provider);
            }

            if (empty($this->ProviderToken)) {
                $result->addFieldError('ProviderToken', 'Required for ' . $this->Provider);
            }
        }

        return $result;
    }
}
