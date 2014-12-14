<?php
namespace Craft;

class Postmaster_NotificationModel extends Postmaster_BasePluginModel
{ 
    protected $_notificationType;

    protected $_notificationSchedule;

    public function __construct($attributes = null)
    {
        parent::__construct($attributes);

        if(is_array($this->settings))
        {
            $this->setAttribute('settings', new Postmaster_NotificationSettingsModel($this->settings));
        }
    }

    public function init()
    {
        parent::init();

        $this->getNotificationType()->init();
        $this->getNotificationSchedule()->init();
    }

    public function getNotificationType($class = false)
    {
        if(is_null($this->_notificationType))
        {
            if(!$class)
            {
                $class = $this->settings->notificationType;
            }

            $class = new $class();

            $settings = $this->settings->getNotificationTypeSettingsById($class->id);

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);
            $class->setNotificationModel($this);

            $this->_notificationType = $class;
        }

        return $this->_notificationType;
    }

    public function getNotificationSchedule($class = false)
    {
        if(is_null($this->_notificationSchedule))
        {
            if(!$class)
            {
                $class = $this->settings->notificationSchedule;
            }

            $class = new $class();

            $settings = $this->settings->getNotificationScheduleSettingsById($class->id);

            if(is_array($settings))
            {
                $settings = $class->createSettingsModel($settings);
            }

            $class->setSettings($settings);
            $class->setNotificationModel($this);

            $this->_notificationSchedule = $class;
        }

        return $this->_notificationSchedule;
    }

    public function marshal()
    {
        $notificationSchedule = $this->getNotificationSchedule();

        if($notificationSchedule->shouldSend($this->lastSent()))
        {
            $notificationType = $this->getNotificationType();
        
            $transport = new Postmaster_TransportModel(array(
                'service' => $this->service,
                'settings' => $notificationType->getSettings(),
                'data' => array()
            ));

            if( $notificationType->onBeforeSend($transport) !== false && 
                $notificationSchedule->onBeforeSend($transport) !== false)
            {
                $response = $this->send($transport);
                    
                // Test the service response for correct class and throw an error if it fails
                if(!$response instanceof \Craft\Postmaster_TransportResponseModel)
                {
                    throw new Exception('The '.$notificationSchedule->service->name.' service did not return a \Craft\Postmaster_TransportResponseModel');
                }

                $notificationSchedule->onAfterSend($response);

                $notificationType->onAfterSend($response);

                // Save the response to the db
                $response->save();

                if($response->getSuccess())
                {
                    craft()->postmaster_notifications->createSentNotification($this);
                }
            }
        }
    }

    public function lastSent()
    {
        return craft()->postmaster_notifications->lastSent($this->id);
    }

    protected function defineAttributes()
    {
        return array(
            'title'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'notificationType'     => array(AttributeType::String, 'column' => ColumnType::Text, 'default' => 'default'),
            'settings'  => array(AttributeType::Mixed, 'column' => ColumnType::LongText, 'default' => array()),
            'enabled'  => array(AttributeType::Bool, 'column' => ColumnType::Int, 'default' => 1),
            'id'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'uid'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateCreated'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'dateUpdated'     => array(AttributeType::String, 'column' => ColumnType::Text),
        );
    }
}