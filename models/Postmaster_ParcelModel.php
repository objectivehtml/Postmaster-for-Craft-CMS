<?php
namespace Craft;

class Postmaster_ParcelModel extends BaseModel
{ 
    protected $_service;

    protected $_parcelType;

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
        $parcelType = $this->getParcelType();
        $service = $this->getService();

        $parcelType->init();
        $service->init();
    }

	public function getTableName()
    {
        return 'postmaster_parcels';
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

    public function getParcelTypeSettingsById($id)
    {
        return $this->settings->getParcelTypeSettingsById($id);
    }

    public function setParcelTypeSettings($id, Array $settings = array())
    {     
        return $this->settings->setParcelTypeSettings($id, $settings);
    }

    public function getServiceSettingsByid($id)
    {
        return $this->settings->getServiceSettingsById($id);
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

            $settings = $this->getServiceSettingsById($class->id);

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);

            $this->_service = $class;
        }

        return $this->_service;
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

            $settings = $this->getParcelTypeSettingsById($class->id);

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


    public function send(Postmaster_TransportModel $model)
    {
        return craft()->postmaster->send($model);
    }

    protected function defineAttributes()
    {
        return array(
            'title'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'parcelType'     => array(AttributeType::String, 'column' => ColumnType::Text, 'default' => 'default'),
            'settings'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText, 'default' => array()),
            'enabled'  => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
            'id'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'uid'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateCreated'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateUpdated'     => array(AttributeType::String, 'column' => ColumnType::Text),
        );
    }
}