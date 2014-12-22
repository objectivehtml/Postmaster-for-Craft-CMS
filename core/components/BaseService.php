<?php
namespace Craft\Plugins\Postmaster\Components;

use Craft\BaseModel;
use Craft\Postmaster_TransportModel;
use Craft\Postmaster_TransportResponseModel;
use Craft\Plugins\Postmaster\Interfaces\ServiceInterface;

abstract class BaseService extends BasePlugin implements ServiceInterface {

	protected $requireModels = array();

	public function getInputHtml(Array $data = array())
	{
		return '';
	}

	public function onBeforeSend(Postmaster_TransportModel $model)
	{
		// Do something before the email sends
	}

	public function onAfterSend(Postmaster_TransportResponseModel $model)
	{
		// Do something after the email sends
	}

	public function getRequiredModels()
	{
		return $this->requireModels;
	}

	public function setRequiredModels(Array $models)
	{
		$this->requireModels = $models;
	}

	public function validateModel(BaseModel $model)
	{
		$required = $this->getRequiredModels();

		if(!count($required))
		{
			return true;
		}

		if(in_array(get_class($model), $required))
		{
			return true;
		}

		foreach($this->requireModels as $class)
		{
			if(is_subclass_of($model, $class))
			{
				return true;
			}
		}

		return false;
	}
	
	public function success(Postmaster_TransportModel $model, $code = 200)
	{
		return new Postmaster_TransportResponseModel(array(
			'service' => $this,
			'model' => $model,
			'code' => $code
		));
	}

	public function failed(Postmaster_TransportModel $model, $code = 400, Array $errors = array())
	{
		return new Postmaster_TransportResponseModel(array(
			'service' => $this,
			'model' => $model,
			'success' => false,
			'code' => $code,
			'errors' => $errors
		));
	}
}