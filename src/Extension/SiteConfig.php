<?php

namespace EdgarIndustries\ElementalMap\Extension;

use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class SiteConfig extends DataExtension
{
    private static $db = [
        'ElementalMapHereId' => 'Varchar(100)',
        'ElementalMapHereToken' => 'Varchar(100)',
        'ElementalMapMapboxId' => 'Varchar(100)',
        'ElementalMapMapboxToken' => 'Varchar(100)',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName([
            'ElementalMapHereId',
            'ElementalMapHereToken',
            'ElementalMapMapboxId',
            'ElementalMapMapboxToken'
        ]);

        $fields->addFieldsToTab('Root.MapCredentials', [
            FieldGroup::create(
                'HERE Maps',
                TextField::create('ElementalMapHereId', 'App ID'),
                TextField::create('ElementalMapHereToken', 'App Code')
            ),
            FieldGroup::create(
                'Mapbox',
                TextField::create('ElementalMapMapboxId', 'Map ID'),
                TextField::create('ElementalMapMapboxToken', 'Access Token')
            ),
        ]);
    }
}
