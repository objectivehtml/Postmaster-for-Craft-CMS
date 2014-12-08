<?php
namespace Craft;

class Postmaster_ParcelSettingsModel extends BaseModel
{
	protected function defineAttributes()
    {
        return array(
            'htmlTemplate' => AttributeType::String,
            'plainTemplate' => AttributeType::String,
            'parcelType' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType'),
            'parcelTypeSettings' => array(AttributeType::Mixed, 'default' => array()),
            'service' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\Services\CraftService'),
            'serviceSettings' => array(AttributeType::Mixed, 'default' => array()),
            'enabled' => AttributeType::Number
        );
    }
}