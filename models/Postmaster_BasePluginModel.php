<?php
namespace Craft;

class Postmaster_BasePluginModel extends Postmaster_BaseSettingsModel
{
    protected $_service;

    public function init()
    {
        $this->getService()->init();
    }

    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($key)
    {
        if(isset($this->settings->$key))
        {
            return $this->settings->$key;
        }

        return;
    }

    public function setServiceSettings($id, Array $settings = array())
    {        
        return $this->settings->setServiceSettings($id, $settings);
    }

    public function getService($class = false)
    {
        if(is_null($this->_service))
        {
            if(!$class)
            {
                $class = $this->settings->service;
            }

            $class = new $class();

            $settings = $this->settings->getServiceSettingsById($class->getId());

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);

            $this->_service = $class;
        }

        return $this->_service;
    }

    public function send(Postmaster_TransportModel $model)
    {
        return craft()->postmaster->send($model);
    }
}