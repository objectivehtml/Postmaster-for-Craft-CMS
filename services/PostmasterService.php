<?php
namespace Craft;

class PostmasterService extends BaseApplicationComponent
{
	protected $_services = array();

	protected $_servicesIds = array();
	
	protected $_parcelTypes = array();

	protected $_parcelTypesIds = array();
	
	protected $_notificationTypes = array();

	protected $_notificationTypesIds = array();

	protected $_notificationSchedules = array();

	protected $_notificationSchedulesIds = array();

	protected $_parcelSchedules = array();

	protected $_parcelSchedulesIds = array();

	public function onInit(Event $event)
	{
		$this->raiseEvent('onInit', $event);
	}

	public function createTemplateUrls($plain, $html)
	{
		$record = new Postmaster_MessageTemplateRecord;
		$record->plain = $plain;
		$record->html = $html;
		$record->save();

		return array(
			'text' => UrlHelper::getSiteUrl('postmaster/template/text/' . $record->id),
			'html' => UrlHelper::getSiteUrl('postmaster/template/html/' . $record->id)
		);
	}

	public function parcels($criteria = false)
    {
        return new Postmaster_ParcelCriteriaModel($criteria ?: array());
    }

	public function notifications($criteria = false)
    {
        return new Postmaster_NotificationCriteriaModel($criteria ?: array());
    }

	public function transportResponses($criteria = false)
    {
        return new Postmaster_TransportResponseCriteriaModel($criteria ?: array());
    }

    public function service($class, $settings = null)
    {
    	if(!is_array($settings))
    	{
    		$settings = array();
    	}

    	$class = '\Craft\Plugins\Postmaster\Services\\' . $class . 'Service';

    	if(class_exists($class))
    	{
    		return new $class($settings);
    	}

    	return;
    }

    /*
     * Send a Postmaster_TransportModel object to the queue
     *
     * @param  Postmaster_TransportModel
     * @return object
    */
    public function sendToQueue(Postmaster_TransportModel $model)
    {
    	// Creat the queue record and save it to the db
    	$record = new Postmaster_QueueRecord();
    	$record->model = $model;
		$record->sendDate = $model->getSendDate();
		$record->save();

		// set the queue record id to the Postmaster_TransportModel object
		$model->queueId = $record->id;

		// Return Postmaster_TransportResponseModel as normal even though no message was sent
		// Since the Postmaster_TransportModel has a queueId, it won't be saved to the db
		return new Postmaster_TransportResponseModel(array(
			'service' => $model->service,
			'model' => $model
		)); 
    }

    /*
     * Send a Postmaster_TransportModel object
     *
     * @param  Postmaster_TransportModel
     * @return Mixed
    */
    public function send(Postmaster_TransportModel $model)
    {
    	// Validate the model to ensure the service is compatible with it
    	if(!$model->service->validateModel($model->settings))
    	{
    		$message = get_class($model->service) . ' is not compatible with ' . get_class($model->settings);

    		throw new Exception(Craft::t($message));
    	}

    	// If the model has a queueId remove the record from the queue
		if($model->queueId)
		{
			craft()->postmaster_queue->remove($model->queueId);
		}
		
    	//Validate the model to see if the send past has not past
    	if($model->shouldSend())
    	{
	    	// Triger onBeforeSend method, and if return false then fail
	        if($model->service->onBeforeSend($model) !== false)
	        {
	        	// Send the Postmaster_TransportModel model to the service in
	        	// exchange for a Postmaster_TransportResponseModel object
	            $response = $model->service->send($model);
	           	
	           	// Test the service response for correct class and throw an error if it fails
	           	if(!$response instanceof \Craft\Postmaster_TransportResponseModel)
	           	{
	           		throw new Exception('The '.$model->service->getName().' service did not return a \Craft\Postmaster_TransportResponseModel');
	           	}

	           	// Trigger the onAfterSend method
	            $model->service->onAfterSend($response);

	            // Save the response to the db
	            $response->save();

	            // Return the actual response
	            return $response;
	        }
	    }
	    else
	    {
	    	// Send the model to the queue to be sent later
	    	return $this->sendToQueue($model);
	    }

        // Return false if anything fails
        return false;
    }

	public function registerServices(Array $services = array())
	{
		$this->_registerObjects('_services', $services, '\Craft\Plugins\Postmaster\Components\BaseService');
	}

	public function registerService($class)
	{
		return $this->_registerObject('_services', $class, '\Craft\Plugins\Postmaster\Components\BaseService');
	}

	public function registerParcelTypes(Array $parcels = array())
	{
		$this->_registerObjects('_parcelTypes', $parcels, '\Craft\Plugins\Postmaster\Components\BaseParcelType');
	}

	public function registerParcelType($class)
	{
		return $this->_registerObject('_parcelTypes', $class, '\Craft\Plugins\Postmaster\Components\BaseParcelType');
	}

	public function registerNotificationTypes(Array $notifications = array())
	{
		return $this->_registerObjects('_notificationTypes', $notifications, '\Craft\Plugins\Postmaster\Components\BaseNotificationType');
	}

	public function registerNotificationType($class)
	{
		return $this->_registerObject('_notificationTypes', $class, '\Craft\Plugins\Postmaster\Components\BaseNotificationType');
	}

	public function registerNotificationSchedules(Array $schedules = array())
	{
		return $this->_registerObjects('_notificationSchedules', $schedules, '\Craft\Plugins\Postmaster\Components\BaseNotificationSchedule');
	}

	public function registerNotificationSchedule($class)
	{
		return $this->_registerObject('_notificationSchedules', $class, '\Craft\Plugins\Postmaster\Components\BaseNotificationSchedule');
	}

	public function registerParcelSchedules(Array $schedules = array())
	{
		return $this->_registerObjects('_parcelSchedules', $schedules, '\Craft\Plugins\Postmaster\Components\BaseParcelSchedule');
	}

	public function registerParcelSchedule($class)
	{
		return $this->_registerObject('_parcelSchedules', $class, '\Craft\Plugins\Postmaster\Components\BaseParcelSchedule');
	}

	public function getRegisteredServices()
	{
		return $this->_services;
	}

	public function getRegisteredService($id)
	{
		return isset($this->_servicesIds[$id]) ? $this->_servicesIds[$id] : null;
	}

	public function getRegisteredParcelTypes()
	{
		return $this->_parcelTypes;
	}

	public function getRegisteredParcelType($id)
	{
		return isset($this->_parcelTypesIds[$id]) ? $this->_parcelTypesIds[$id] : null;
	}

	public function getRegisteredNotificationTypes()
	{
		return $this->_notificationTypes;
	}

	public function getRegisteredNotificationType($id)
	{
		return isset($this->_notificationTypesIds[$id]) ? $this->_notificationTypesIds[$id] : null;
	}

	public function getRegisteredNotificationSchedules()
	{
		return $this->_notificationSchedules;
	}

	public function getRegisteredNotificationSchedule($id)
	{
		return isset($this->_notificationSchedulesIds[$id]) ? $this->_notificationSchedulesIds[$id] : null;
	}

	public function getRegisteredParcelSchedules()
	{
		return $this->_parcelSchedules;
	}

	public function getRegisteredParcelSchedule($id)
	{
		return isset($this->_parcelSchedulesIds[$id]) ? $this->_parcelSchedulesIds[$id] : null;
	}

	private function _registerObjects($prop, $objects, $instance)
	{
		foreach($objects as $class)
		{
			$this->_registerObject($prop, $class, $instance);
		}
	}

	private function _registerObject($prop, $class, $instance)
	{
		$obj = new $class();

		if(!$obj instanceof $instance)
		{
			throw new Exception(Craft::t('The class "'.$class.'" is not an instance of '.get_class($instance).'.'));
		}

		$objects = $this->$prop;

		if(!isset($objects[$obj->getName()]))
		{
			if(!array_key_exists($obj->getId(), $this->{$prop.'Ids'}))
			{
				$objects[$obj->getName()] = $obj;

				$this->$prop = $objects;
				$this->{$prop.'Ids'}[$obj->getId()] = $obj;

				return $obj;
			}

			throw new Exception(Craft::t('An object has already been registered that has the id "'.$obj->id.'".'));
		}
		else
		{
			throw new Exception(Craft::t('An object has already been registered with the name "'.$obj->getName().'" belonging to class "'.$class.'"."'));
		}
	}
}