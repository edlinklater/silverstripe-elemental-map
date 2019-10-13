<?php

namespace EdgarIndustries\ElementalMap\Block;

use DNADesign\Elemental\Models\BaseElement;
use EdgarIndustries\ElementalMap\Model\MapMarker;
use EdgarIndustries\ElementalMap\Provider\Here;
use EdgarIndustries\ElementalMap\Provider\HereHybrid;
use EdgarIndustries\ElementalMap\Provider\Mapbox;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\SiteConfig\SiteConfig;

class MapBlock extends BaseElement
{
    private static $icon = 'font-icon-rocket';

    private static $db = [
        'Provider' => 'Varchar(255)',
        'Height' => 'Int',
        'Width' => 'Int',
        'DefaultLatitude' => 'Decimal(9,6)',
        'DefaultLongitude' => 'Decimal(9,6)',
        'DefaultZoom' => 'Int',
    ];

    private static $many_many = [
        'Markers' => MapMarker::class,
    ];

    private static $defaults = [
        'DefaultZoom' => 14,
        'Height' => 400,
    ];

    private static $singular_name = 'map';

    private static $plural_name = 'maps';

    private static $table_name = 'Edgar_EB_MapBlock';

    private static $inline_editable = false;

    private static $providers = [
        Here::class,
        HereHybrid::class,
        Mapbox::class,
    ];

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {

            $fields->removeByName([
                'Provider',
                'ProviderVariant',
                'DefaultLatitude',
                'DefaultLongitude',
                'DefaultZoom',
                'LinkTracking',
                'FileTracking',
                'Markers',
                'Height',
                'Width',
            ]);

            $currentProvider = class_exists($this->Provider) ? Injector::inst()->create($this->Provider) : null;

            $providers = ArrayList::create();
            foreach ($this->config()->get('providers') as $providerClass) {
                $provider = Injector::inst()->create($providerClass);
                $providers->push((object)['ID' => $providerClass, 'Title' => $provider->getTitle()]);
            }

            $fields->addFieldsToTab('Root.Main', [
                DropdownField::create('Provider', 'Tiles', $providers->sort('Title')->map()),
                FieldGroup::create(
                    'Centre',
                    NumericField::create('DefaultLatitude', 'Latitude')->setScale(6),
                    NumericField::create('DefaultLongitude', 'Longitude')->setScale(6),
                    TextField::create('DefaultZoom', 'Zoom')
                ),
                FieldGroup::create(
                    'Size',
                    NumericField::create('Height'),
                    NumericField::create('Width', 'Width (optional)')
                ),
                LiteralField::create('MarkersPadding', '<p style="height: 25px">&nbsp;</p>'),
                GridField::create(
                    'Markers',
                    'Markers',
                    $this->Markers()
                )->setConfig(GridFieldConfig_RelationEditor::create()),
            ]);
        });

        return parent::getCMSFields();
    }

    public function getLeafletParams()
    {
        $currentProvider = class_exists($this->Provider) ? Injector::inst()->create($this->Provider) : null;

        return $currentProvider ? json_encode($currentProvider->getLeafletParams()) : false;
    }

    public function getTileUrl()
    {
        $currentProvider = class_exists($this->Provider) ? Injector::inst()->create($this->Provider) : null;

        return $currentProvider ? $currentProvider->getTileUrl() : false;
    }

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Map');
    }

    public function validate()
    {
        $result = parent::validate();
        $currentProvider = class_exists($this->Provider) ? Injector::inst()->create($this->Provider) : null;
        $siteconfig = SiteConfig::current_site_config();

        if (empty($this->Height)) {
            $result->addFieldError('Height', 'Height is required');
        }

        if ($currentProvider) {
            $providerAuth = $currentProvider->requiresAuth();
            $invalid = false;

            foreach ($providerAuth as $key) {
                if (empty($siteconfig->$key)) {
                    $invalid = true;
                }
            }

            if ($invalid) {
                $result->addFieldError(
                    'Provider',
                    'This provider requires authentication configuration in Settings > Map Credentials'
                );
            }
        }

        return $result;
    }
}
