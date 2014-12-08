<?php
namespace Craft;

class PostmasterService extends BaseApplicationComponent
{
	protected $_services = array();
	
	protected $_parcelTypes = array();

	public function parcels($criteria = false)
    {
        return new Postmaster_ParcelCriteriaModel($criteria ?: array());
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

    	// Triger onBeforeSend method, and if return false then fail
        if($model->service->onBeforeSend() !== false)
        {
        	// Send the Postmaster_TransportModel model to the service in
        	// exchange for a Craft\Plugins\Postmaster\Responses\TransportResponse object
            $response = $model->service->send($model);
           	
           	// Trigger the onAfterSend method
            $model->service->onAfterSend();

            // Save the response to the db
            $response->save();
            
            // Return the actual response
            return $response;
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

	public function getRegisteredServices()
	{
		return $this->_services;
	}

	public function getRegisteredParcelTypes()
	{
		return $this->_parcelTypes;
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

		if(!isset($objects[$obj->name]))
		{
			$objects[$obj->name] = $obj;

			$this->$prop = $objects;

			return $obj;
		}
		else
		{
			throw new Exception(Craft::t('An object has already been registered with the name "'.$name.'" belonging to class "'.$class.'"."'));
		}
	}
}