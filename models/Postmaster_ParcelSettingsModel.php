<?php
namespace Craft;

class Postmaster_ParcelSettingsModel extends BaseModel
{
    public function __construct($attributes = null)
    {
        parent::__construct($attributes);
    }

    public function setAttributes($attributes = null)
    {
        parent::setAttributes($attributes);

        $this->_sanitizeSettings();
    }

    public function getServiceSettingsById($id)
    {
        $settings = array();

        if(isset($this->serviceSettings[$id]))
        {
            $settings = $this->serviceSettings[$id];
        }

        return $settings;
    }
    
    public function setServiceSettings($id, $settings = array())
    {        
        if(isset($this->serviceSettings[$id]))
        {
            $newSettings = $this->serviceSettings;
            $newSettings[$id] = $settings;

            $this->serviceSettings = $newSettings;
        }
    }

    public function getParcelTypeSettingsById($id)
    {
        $settings = array();

        if(isset($this->parcelTypeSettings[$id]))
        {
            $settings = $this->parcelTypeSettings[$id];
        }

        return $settings;
    }

    public function setParcelTypeSettings($id, $settings = array())
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

    private function _sanitizeSettings()
    {
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

            $this->setParcelTypeSettings($id, $settings);
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

            $this->setServiceSettings($id, $settings);
        }
    }
}