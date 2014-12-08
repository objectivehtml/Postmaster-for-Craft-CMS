<?php
namespace Craft;

class Postmaster_ParcelModel extends BaseModel
{ 
    protected $_service;

    protected $_parcelType;

    public function __construct($attributes = null)
    {
        parent::__construct($attributes);

        if(is_string($this->settings))
        {
            $this->settings = json_decode($this->settings);
        }

        $this->settings = new Postmaster_ParcelSettingsModel((array) $this->settings);
    }

    public function init()
    {
        $parcelType = $this->getParcelType();
        $parcelType->init();
    }

	public function getTableName()
    {
        return 'postmaster_parcels';
    }

    public function getParcelTypeSettings($id)
    {
        $settings = array();

        if(isset($this->settings->parcelTypeSettings[$id]))
        {
            $settings = $this->settings->parcelTypeSettings[$id];
        }

        return $settings;
    }

    public function getServiceSettings($id)
    {
        $settings = array();

        if(isset($this->settings->serviceSettings[$id]))
        {
            $settings = $this->settings->serviceSettings[$id];
        }

        return $settings;
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

    public function getService()
    {
        if(is_null($this->_service))
        {
            $class = $this->settings->service;
            $class = new $class();
            $class->setSettings($class->createSettingsModel($this->getServiceSettings($class->id)));

            $this->_service = $class;
        }

        return $this->_service;
    }

    public function getParcelType()
    {
        if(is_null($this->_parcelType))
        {
            $class = $this->settings->parcelType;
            $class = new $class();
            $class->setSettings($class->createSettingsModel($this->getParcelTypeSettings($class->id)));
            // $class->setService($this->getService());
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
            'settings'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText, 'default' => array()),
            'enabled'  => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
            'id'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'uid'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateCreated'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateUpdated'     => array(AttributeType::String, 'column' => ColumnType::Text),
        );
    }
}