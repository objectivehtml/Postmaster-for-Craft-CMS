<?php
namespace Craft;

class Postmaster_BasePluginSettingsModel extends Postmaster_BaseSettingsModel
{
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

    protected function _sanitizeSettings()
    {
        $serviceSettings = $this->serviceSettings;

        foreach(craft()->postmaster->getRegisteredServices() as $service)
        {
            if(!isset($serviceSettings[$service->getId()]))
            {
                $serviceSettings[$service->getId()] = array();
            }
        }

        $this->serviceSettings = $serviceSettings;

        foreach($this->serviceSettings as $id => $serviceSettings)
        {
            if($class = craft()->postmaster->getRegisteredService($id))
            {
                $settings = $class->createSettingsModel($serviceSettings);

                $this->setServiceSettings($id, $settings);
            }
        }
    }
}