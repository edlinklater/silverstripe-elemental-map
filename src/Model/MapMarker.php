<?php

namespace EdgarIndustries\ElementalMap\Model;

use EdgarIndustries\ElementalMap\Block\MapBlock;
use SilverStripe\ORM\DataObject;

class MapMarker extends DataObject
{
    private static $table_name = 'Edgar_EB_MapMarker';

    private static $db = [
        'Title' => 'Varchar(255)',
        'Description' => 'HTMLText',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
    ];

    private static $belongs_many_many = [
        'Block' => MapBlock::class,
    ];
}
