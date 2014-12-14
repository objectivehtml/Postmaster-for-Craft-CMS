<?php
namespace Craft;

class Postmaster_ParcelModel extends Postmaster_BasePluginModel
{ 
    protected $_parcelType;

    protected $_parcelSchedule;

    public function __construct($attributes = null)
    {
        parent::__construct($attributes);

        if(is_array($this->settings))
        {
            $this->setAttribute('settings', new Postmaster_ParcelSettingsModel($this->settings));
        }
    }

    public function init()
    {
        parent::init();

        $this->getParcelType()->init();
    }

    public function send(Postmaster_TransportModel $transport)
    {
        $lastSent = craft()->postmaster_parcels->lastSent($this->id);

        if($this->getParcelSchedule()->shouldSend($lastSent))
        {
            $parcelType = $this->getParcelType();

            $parcelSchedule = $this->getParcelSchedule();

            if( $parcelType->onBeforeSend($transport) !== false && 
                $parcelSchedule->onBeforeSend($transport) !== false)
            {
                $response = parent::send($transport);
                    
                $parcelSchedule->onAfterSend($response);

                $parcelType->onAfterSend($response);

                if($response->getSuccess())
                {
                    craft()->postmaster_parcels->createSentParcel($this);
                }
            }
        }

        return false;
    }

    public function getParcelType($class = false)
    {
        if(is_null($this->_parcelType))
        {
            if(!$class)
            {
                $class = $this->settings->parcelType;
            }

            $class = new $class();

            $settings = $this->settings->getParcelTypeSettingsById($class->id);

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);
            $class->setParcelModel($this);

            $this->_parcelType = $class;
        }

        return $this->_parcelType;
    }

    public function getParcelSchedule($class = false)
    {
        if(is_null($this->_parcelSchedule))
        {
            if(!$class)
            {
                $class = $this->settings->parcelSchedule;
            }

            $class = new $class();

            $settings = $this->settings->getParcelScheduleSettingsById($class->id);

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);
            $class->setParcelModel($this);

            $this->_parcelSchedule = $class;
        }

        return $this->_parcelSchedule;
    }

    protected function defineAttributes()
    {
        return array(
            'title' => array(AttributeType::String, 'column' => ColumnType::Text),
            'parcelType' => array(AttributeType::String, 'column' => ColumnType::Text, 'default' => 'default'),
            'settings' => array(AttributeType::Mixed, 'column' => ColumnType::LongText, 'default' => array()),
            'enabled' => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
            'id' => array(AttributeType::String, 'column' => ColumnType::Text),
            'uid' => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateCreated' => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateUpdated' => array(AttributeType::String, 'column' => ColumnType::Text),
        );
    }
}