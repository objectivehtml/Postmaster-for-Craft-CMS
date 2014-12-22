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

    public function lastSent()
    {
        return craft()->postmaster_parcels->lastSent($this->id);
    }

    public function onBeforeSend(Event $event)
    {
        $this->raiseEvent('onBeforeSend', $event);

        craft()->postmaster_parcels->onBeforeSend($event);

        return $event;
    }

    public function onAfterSend(Event $event)
    {
        $this->raiseEvent('onAfterSend', $event);

        craft()->postmaster_parcels->onAfterSend($event);

        return $event;
    }

    public function onSendComplete(Event $event)
    {
        $this->raiseEvent('onSendComplete', $event);

        craft()->postmaster_parcels->onSendComplete($event);

        return $event;
    }

    public function onSendFailed(Event $event)
    {
        $this->raiseEvent('onSendFailed', $event);

        craft()->postmaster_parcels->onSendFailed($event);

        return $event;
    }

    public function send(Postmaster_TransportModel $transport)
    {
        // Get the parcel schedule instance
        $parcelSchedule = $this->getParcelSchedule();

        // Check to see if the notification should send by passing the last 
        // time the notification was sent as a Carbon\Carbon object
        if($parcelSchedule->shouldSend($this->lastSent()))
        {
            // Get the parcel type instance
            $parcelType = $this->getParcelType();

            // Call onBeforeSend methods to the parcel schedule and parcel type
            // If either instance returns false, the message will not be sent
            if($parcelSchedule->onBeforeSend($transport) === false)
            {
                return false;
            }

            if($parcelType->onBeforeSend($transport) === false)
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
            $response = parent::send($transport);
                
            // Test the service response for correct class and throw an error if it fails
            if(!$response instanceof \Craft\Postmaster_TransportResponseModel)
            {
                throw new Exception('The '.$parcelSchedule->getService()->getName().' service did not return a \Craft\Postmaster_TransportResponseModel');
            }

            // Call on the onAfterSend method for the parcel schedule
            $parcelSchedule->onAfterSend($response);

            // Call on the onAfterSend method for the parcel type
            $parcelType->onAfterSend($response);

            // Trigger the onAfterSend event to allow developers a change
            // to do something after a parcel has sent.
            $onAfterSendEvent = $this->onAfterSend(new Event($this, array(
                'response' => $response
            )));

            // If the response is a success, create a parcel sent record
            if($response->getSuccess())
            {
                // Pass $this object to the createSentParcel method to
                // create the actual record in the db
                craft()->postmaster_parcels->createSentParcel($this, $response);

                // Call the onSendFailed method on the notification schedule
                $parcelSchedule->onSendComplete($response);

                // Call the onSendFailed method on the notification type
                $parcelType->onSendComplete($response);

                // Trigger the onSendFailed method
                $this->onSendComplete(new Event($this, array(
                    'response' => $response
                )));
            }
            else
            {
                // Call the onSendFailed method on the notification schedule
                $parcelSchedule->onSendFailed($response);

                // Call the onSendFailed method on the notification type
                $parcelType->onSendFailed($response);

                // Trigger the onSendFailed method
                $this->onSendFailed(new Event($this, array(
                    'response' => $response
                )));
            }

            return $response;
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

            $settings = $this->settings->getParcelTypeSettingsById($class->getId());

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

            $settings = $this->settings->getParcelScheduleSettingsById($class->getId());

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