<?php

namespace EdgarIndustries\ElementalMap\Block;

use DNADesign\Elemental\Models\BaseElement;

class MapBlock extends BaseElement
{
    private static $icon = 'font-icon-rocket';

    private static $db = [
        'Provider' => "Enum('HERE.normalDay,HERE.hybridDay,MapBox,OpenStreetMap.Mapnik,OpenTopoMap', 'OpenStreetMap.Mapnik')",
        'ProviderID' => 'Varchar(255)',
        'ProviderToken' => 'Varchar(255)',
        'Height' => 'Int',
        'DefaultLatitude' => 'Decimal(3,9)',
        'DefaultLongitude' => 'Decimal(3,9)',
        'DefaultZoom' => 'Int',
    ];

    private static $singular_name = 'map';

    private static $plural_name = 'maps';

    private static $table_name = 'Edgar_EB_MapBlock';

    protected static $requires_auth = [
        'HERE.normalDay',
        'HERE.hybridDay',
        'MapBox',
    ];

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
