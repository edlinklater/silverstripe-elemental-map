<?php

namespace EdgarIndustries\ElementalMap\Model;

use EdgarIndustries\ElementalMap\Block\MapBlock;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;

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

    private static $summary_fields = [
        'Title',
        'Latitude',
        'Longitude',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(['Latitude', 'Longitude', 'LinkTracking', 'FileTracking', 'Block']);

        $fields->dataFieldByName('Description')->setRows(4);

        $fields->addFieldsToTab('Root.Main', [
            FieldGroup::create(
                'Position',
                TextField::create('Latitude'),
                TextField::create('Longitude')
            ),
            LiteralField::create('BlocksPadding', '<p style="height: 25px">&nbsp;</p>'),
            GridField::create(
                'Block',
                'Show on Maps',
                $this->Block()
            )->setConfig(GridFieldConfig_RelationEditor::create()
                ->removeComponentsByType(GridFieldAddNewButton::class))
        ]);

        return $fields;
    }

    public function getPopupContent()
    {
        $content = '<h4 class="edgarindustries__elementalmap__block__marker__header">' . $this->Title . '</h4>';
        $content .= '<div class="edgarindustries__elementalmap__block__marker__content">' . $this->Description . '</div>';

        $content = str_replace("'", "\\'", $content);

        return DBHTMLText::create()->setValue($content);
    }
}
