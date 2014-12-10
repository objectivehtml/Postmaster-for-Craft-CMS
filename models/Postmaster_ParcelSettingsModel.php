<?php
namespace Craft;

class Postmaster_ParcelSettingsModel extends BaseModel
{
    public function __construct($attributes = null)
    {
        parent::__construct($attributes);

        $parcelTypeSettings = $this->parcelTypeSettings;

        foreach(craft()->postmaster->getRegisteredParcelTypes() as $parcelType)
        {
            if(!isset($parcelTypeSettings[$parcelType->id]))
            {
                $parcelTypeSettings[$parcelType->id] = array();
            }
        }

        $this->parcelTypeSettings = $parcelTypeSettings;

        foreach($this->parcelTypeSettings as $id => $parcelTypeSettings)
        {
            $class = craft()->postmaster->getRegisteredParcelType($id);

            $settings = $class->createSettingsModel($parcelTypeSettings);

            $this->setParcelTypeSettings($id, $settings->getAttributes());
        }


        $serviceSettings = $this->serviceSettings;

        foreach(craft()->postmaster->getRegisteredServices() as $service)
        {
            if(!isset($serviceSettings[$service->id]))
            {
                $serviceSettings[$service->id] = array();
            }
        }

        $this->serviceSettings = $serviceSettings;

        foreach($this->serviceSettings as $id => $serviceSettings)
        {
            $class = craft()->postmaster->getRegisteredService($id);

            $settings = $class->createSettingsModel($serviceSettings);

            $this->setServiceSettings($id, $settings->getAttributes());
        }

    }

    public function getServiceSettings($id)
    {
        $settings = array();

        if(isset($this->serviceSettings[$id]))
        {
            $settings = $this->serviceSettings[$id];
        }

        return $settings;
    }
    
    public function setServiceSettings($id, Array $settings = array())
    {        
        if(isset($this->serviceSettings[$id]))
        {
            $newSettings = $this->serviceSettings;
            $newSettings[$id] = $settings;

            $this->serviceSettings = $newSettings;
        }
    }

    public function getParcelTypeSettings($id)
    {
        $settings = array();

        if(isset($this->parcelTypeSettings[$id]))
        {
            $settings = $this->parcelTypeSettings[$id];
        }

        return $settings;
    }

    public function setParcelTypeSettings($id, Array $settings = array())
    {        
        if(isset($this->parcelTypeSettings[$id]))
        {
            $newSettings = $this->parcelTypeSettings;
            $newSettings[$id] = $settings;

            $this->parcelTypeSettings = $newSettings;
        }
    }

	protected function defineAttributes()
    {
        return array(
            'parcelType' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType'),
            'parcelTypeSettings' => array(AttributeType::Mixed, 'default' => array()),
            'service' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\Services\CraftService'),
            'serviceSettings' => array(AttributeType::Mixed, 'default' => array())
        );
    }
}