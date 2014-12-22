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

            $settings = $this->settings->getNotificationTypeSettingsById($class->getId());

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

            $settings = $this->settings->getNotificationScheduleSettingsById($class->getId());

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

    public function onBeforeSend(Event $event)
    {
        $this->raiseEvent('onBeforeSend', $event);

        craft()->postmaster_notifications->onBeforeSend($event);

        return $event;
    }

    public function onAfterSend(Event $event)
    {
        $this->raiseEvent('onAfterSend', $event);

        craft()->postmaster_notifications->onAfterSend($event);

        return $event;
    }

    public function onSendComplete(Event $event)
    {
        $this->raiseEvent('onSendComplete', $event);

        craft()->postmaster_notifications->onSendComplete($event);

        return $event;
    }

    public function onSendFailed(Event $event)
    {
        $this->raiseEvent('onSendFailed', $event);

        craft()->postmaster_notifications->onSendFailed($event);

        return $event;
    }

    public function marshal()
    {
        // Get the notification schedule instance
        $notificationSchedule = $this->getNotificationSchedule();

        // Check to see if the notification should send by passing the last 
        // time the notification was sent as a Carbon\Carbon object
        if($notificationSchedule->shouldSend($this->lastSent()))
        {
            // Get the notification type instance
            $notificationType = $this->getNotificationType();
            
            // Create a new Postmaster_TransportModel object
            $transport = new Postmaster_TransportModel(array(
                'service' => $this->service,
                'settings' => $notificationType->getSettings(),
                'data' => array()
            ));

            // Call onBeforeSend methods to the notification schedule and notification type
            // If either instance returns false, the message will not be sent
            if($notificationSchedule->onBeforeSend($transport) === false)
            {
                return false;
            }

            if($notificationType->onBeforeSend($transport) === false)
            {
                return false;
            }

            // Trigger the onBeforeSend event and allow developers one more
            // change to take over the parcel before it is sent
            $onBeforeSendEvent = $this->onBeforeSend(new Event($this, array(
                'transport' => $transport
            )));

            // See if the action should still be performed and if not, return false
            if($onBeforeSendEvent->performAction === false)
            {
                return false;
            }

            // Send the transport object
            $response = $this->send($transport);
                
            // Test the service response for correct class and throw an error if it fails
            if(!$response instanceof \Craft\Postmaster_TransportResponseModel)
            {
                throw new Exception('The '.$notificationSchedule->getService()->getName().' service did not return a \Craft\Postmaster_TransportResponseModel');
            }

            // Call the onAfterSend method on the notification schedule
            $notificationSchedule->onAfterSend($response);

            // Call the onAfterSend method on the notification type
            $notificationType->onAfterSend($response);

            // Trigger the onAfterSend event to allow developers a change
            // to do something after a parcel has sent.
            $onAfterSendEvent = $this->onAfterSend(new Event($this, array(
                'response' => $response
            )));

            // If the response is a success, create a notification sent record
            if($response->getSuccess())
            {
                // Pass $response object to the createSentNotification method to
                // create the actual record in the db
                craft()->postmaster_notifications->createSentNotification($this, $response);

                // Call the onSendComplete method on the notification schedule
                $notificationSchedule->onSendComplete($response);

                // Call the onSendComplete method on the notification type
                $notificationType->onSendComplete($response);

                // Trigger the onSendComplete method
                $this->onSendComplete(new Event($this, array(
                    'response' => $response
                )));
            }
            else
            {
                // Call the onSendFailed method on the notification schedule
                $notificationSchedule->onSendFailed($response);

                // Call the onSendFailed method on the notification type
                $notificationType->onSendFailed($response);

                // Trigger the onSendFailed method
                $this->onSendFailed(new Event($this, array(
                    'response' => $response
                )));
            }

            return $response;
        }

        return false;
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