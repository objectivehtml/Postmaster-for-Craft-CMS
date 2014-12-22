<?php
namespace Craft;

class Postmaster_ParcelSettingsModel extends Postmaster_BasePluginSettingsModel
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

    public function getParcelScheduleSettingsById($id)
    {
        $settings = array();

        if(isset($this->parcelScheduleSettings[$id]))
        {
            $settings = $this->parcelScheduleSettings[$id];
        }

        return $settings;
    }

    public function setParcelScheduleSettings($id, $settings = array())
    {        
        if(isset($this->parcelScheduleSettings[$id]))
        {
            $newSettings = $this->parcelScheduleSettings;
            $newSettings[$id] = $settings;

            $this->parcelScheduleSettings = $newSettings;
        }
    }

    protected function _sanitizeSettings()
    {
        parent::_sanitizeSettings();

        $parcelTypeSettings = $this->parcelTypeSettings;

        foreach(craft()->postmaster->getRegisteredParcelTypes() as $parcelType)
        {
            if(!isset($parcelTypeSettings[$parcelType->getId()]))
            {
                $parcelTypeSettings[$parcelType->getId()] = array();
            }
        }

        $this->parcelTypeSettings = $parcelTypeSettings;

        foreach($this->parcelTypeSettings as $id => $parcelTypeSettings)
        {
            if($class = craft()->postmaster->getRegisteredParcelType($id))
            {
                $settings = $class->createSettingsModel($parcelTypeSettings);

                $this->setParcelTypeSettings($id, $settings);
            }
        }

        $parcelScheduleSettings = $this->parcelScheduleSettings;

        foreach(craft()->postmaster->getRegisteredParcelSchedules() as $parcelSchedule)
        {
            if(!isset($parcelScheduleSettings[$parcelSchedule->getId()]))
            {
                $parcelScheduleSettings[$parcelSchedule->getId()] = array();
            }
        }

        $this->parcelScheduleSettings = $parcelScheduleSettings;

        foreach($this->parcelScheduleSettings as $id => $parcelScheduleSettings)
        {
            if($class = craft()->postmaster->getRegisteredParcelSchedule($id))
            {
                $settings = $class->createSettingsModel($parcelScheduleSettings);

                $this->setParcelScheduleSettings($id, $settings);
            }
        }
    }

    protected function defineAttributes()
    {
        return array(
            'parcelType' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\ParcelTypes\DefaultParcelType'),
            'parcelTypeSettings' => array(AttributeType::Mixed, 'default' => array()),
            'service' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\Services\CraftService'),
            'serviceSettings' => array(AttributeType::Mixed, 'default' => array()),
            'parcelSchedule' => array(AttributeType::String, 'default' => 'Craft\Plugins\Postmaster\Services\CraftService'),
            'parcelScheduleSettings' => array(AttributeType::Mixed, 'default' => array()),
        );
    }
}