<?php
namespace Craft;

class Postmaster_ParcelSettingsModel extends BaseModel
{
    public function __construct($attributes = null)
    {
        parent::__construct($attributes);

        if(!count($this->serviceSettings))
        {
            $serviceSettings = array();

            foreach(craft()->postmaster->getRegisteredServices() as $service)
            {
                $serviceSettings[$service->id] = $service->createSettingsModel();
            }

            $this->serviceSettings = $serviceSettings;
        }

        if(!count($this->parcelTypeSettings))
        {
            $parcelTypeSettings = array();

            foreach(craft()->postmaster->getRegisteredParcelTypes() as $parcelType)
            {
                $parcelTypeSettings[$parcelType->id] = $parcelType->createSettingsModel();
            }

            $this->parcelTypeSettings = $parcelTypeSettings;
        }
    }

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